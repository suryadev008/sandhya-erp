<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ============================================================================
 * CheckSystemLicense Middleware — Sandhya ERP
 * ============================================================================
 *
 * Yeh middleware poore ERP panel ko payment deadline ke baad automatically
 * lock kar deta hai. Koi bhi page open karne pe pehle yeh check hota hai.
 *
 * ============================================================================
 * SYSTEM REQUIREMENTS
 * ============================================================================
 *
 *   - PHP        : ^8.2
 *   - Laravel    : ^11.x
 *   - Database   : MySQL 5.7+ / MariaDB 10.3+
 *   - Web Server : Apache (XAMPP) / Nginx
 *
 * ============================================================================
 * KAAM KAISE KARTA HAI (Flow)
 * ============================================================================
 *
 *   Har HTTP Request
 *         |
 *         v
 *   Route check: system.locked ya system.unlock?
 *         | YES → bypass (infinite loop se bachao)
 *         | NO
 *         v
 *   PAYMENT_RECEIVED=true (.env)?
 *         | YES → access allow ✓
 *         | NO
 *         v
 *   Session mein system_unlocked=true?
 *         | YES → access allow ✓
 *         | NO
 *         v
 *   Date > 30 April 2026 (end of day)?
 *         | NO  → access allow ✓
 *         | YES
 *         v
 *   AJAX request?
 *         | YES → JSON 403 response
 *         | NO  → Lock Screen pe redirect 🔒
 *
 * ============================================================================
 * LOCK / UNLOCK KE TARIKE
 * ============================================================================
 *
 *   [1] PERMANENT UNLOCK (Payment aane ke baad)
 *       .env mein set karo:
 *           PAYMENT_RECEIVED=true
 *       Phir command chalao:
 *           php artisan config:clear
 *
 *   [2] EMERGENCY SESSION UNLOCK (Temporarily)
 *       Lock screen pe SYSTEM_UNLOCK_KEY (.env wali) dalo.
 *       Sirf usi browser session ke liye unlock hoga.
 *       Browser band = dobara lock.
 *
 * ============================================================================
 * .ENV VARIABLES (zaroori)
 * ============================================================================
 *
 *   PAYMENT_RECEIVED=false
 *       - false : date ke baad lock hoga
 *       - true  : hamesha ke liye unlock (payment confirm)
 *
 *   SYSTEM_UNLOCK_KEY=SandhyaERP@2026#Unlock
 *       - Emergency lock screen bypass key
 *       - Sirf authorized person ko pata honi chahiye
 *       - Apni marzi ki key rakh sakte ho
 *
 * ============================================================================
 * RELATED FILES
 * ============================================================================
 *
 *   Middleware     : app/Http/Middleware/CheckSystemLicense.php  ← (yeh file)
 *   Config         : config/license.php
 *   Lock View      : resources/views/system_locked.blade.php
 *   Routes         : routes/web.php  (system.locked, system.unlock)
 *   Registration   : bootstrap/app.php  (web middleware mein append hai)
 *
 * ============================================================================
 * FRESH SYSTEM PE SETUP (Migration Steps)
 * ============================================================================
 *
 *   STEP 1 — XAMPP Install karo (PHP 8.2 wala)
 *       https://www.apachefriends.org/
 *       Apache aur MySQL start karo Control Panel se.
 *
 *   STEP 2 — Composer Install karo
 *       https://getcomposer.org/Composer-Setup.exe
 *
 *   STEP 3 — Project copy karo
 *       Folder rakho: C:\xampp\htdocs\sandhya_erp\
 *
 *   STEP 4 — Database import karo
 *       "C:\xampp\mysql\bin\mysql.exe" -u root -p
 *       mysql> CREATE DATABASE sandhya_erp;
 *       mysql> EXIT;
 *       "C:\xampp\mysql\bin\mysql.exe" -u root -p sandhya_erp < sandhya_erp_backup.sql
 *
 *   STEP 5 — .env configure karo
 *       APP_URL=http://localhost/sandhya_erp/public
 *       DB_HOST=127.0.0.1
 *       DB_PORT=3306          ← XAMPP default (original machine pe 3308 tha)
 *       DB_DATABASE=sandhya_erp
 *       DB_USERNAME=root
 *       DB_PASSWORD=          ← XAMPP mein default blank hota hai
 *       PAYMENT_RECEIVED=false
 *       SYSTEM_UNLOCK_KEY=SandhyaERP@2026#Unlock
 *
 *   STEP 6 — Dependencies install karo
 *       cd C:\xampp\htdocs\sandhya_erp
 *       composer install --no-dev --optimize-autoloader
 *       npm install && npm run build
 *
 *   STEP 7 — Laravel setup karo
 *       php artisan key:generate
 *       php artisan config:clear
 *       php artisan cache:clear
 *       php artisan route:clear
 *       php artisan view:clear
 *       php artisan storage:link
 *
 *   STEP 8 — Browser mein kholo
 *       http://localhost/sandhya_erp/public
 *
 * ============================================================================
 * COMMON ERRORS AUR FIX
 * ============================================================================
 *
 *   SQLSTATE: Connection refused
 *       → DB_PORT check karo (.env mein 3306 hona chahiye XAMPP pe)
 *
 *   APP_KEY missing / blank screen
 *       → php artisan key:generate chalao
 *
 *   Class not found
 *       → composer install dobara chalao
 *
 *   Route not found / 404
 *       → php artisan route:clear chalao
 *       → Apache mod_rewrite enable hai? httpd.conf check karo
 *
 *   Storage permission denied
 *       → storage/ folder pe right-click → Properties → Security → Full Control
 *
 *   Page styling nahi aa rahi
 *       → npm install && npm run build chalao
 *       → php artisan view:clear chalao
 *
 * ============================================================================
 * LOCK DATE CHANGE KARNA HO TO
 * ============================================================================
 *
 *   Is file mein neeche LOCK_DATE constant update karo:
 *       public const LOCK_DATE = 'YYYY-MM-DD';
 *   Phir:
 *       php artisan config:clear
 *
 * ============================================================================
 *
 * @package App\Http\Middleware
 */
class CheckSystemLicense
{
    /**
     * System lock hone ki date.
     *
     * Is date ke baad (end of day) system lock ho jayega
     * agar `PAYMENT_RECEIVED=false` ho .env mein.
     *
     * Format: YYYY-MM-DD
     *
     * @var string
     */
    public const LOCK_DATE = '2026-04-30';

    /**
     * Incoming request handle karo.
     *
     * - `system.locked` aur `system.unlock` routes ko bypass karta hai
     *   (infinite redirect loop se bachne ke liye).
     * - AJAX/JSON requests pe 403 JSON response deta hai.
     * - Normal requests pe lock screen pe redirect karta hai.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Lock screen routes ko bypass karo (infinite loop se bachao)
        if ($request->routeIs('system.locked') || $request->routeIs('system.unlock')) {
            return $next($request);
        }

        if ($this->isSystemLocked()) {
            // AJAX requests ke liye JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'System locked. Please contact support.',
                    'locked' => true,
                ], 403);
            }

            return redirect()->route('system.locked');
        }

        return $next($request);
    }

    /**
     * Check karo ki system lock hona chahiye ya nahi.
     *
     * Lock hone ke liye teeno conditions sahi honi chahiye:
     *   1. `PAYMENT_RECEIVED` false ho
     *   2. Session mein manual unlock na ho
     *   3. Current datetime, LOCK_DATE ke end of day se aage ho
     *
     * @return bool  true = system lock karo | false = access allow karo
     */
    private function isSystemLocked(): bool
    {
        // Agar payment aa gayi to unlock hai (.env PAYMENT_RECEIVED=true)
        if (config('license.payment_received', false)) {
            return false;
        }

        // Secret key se session unlock kiya ho to bypass karo
        if (session('system_unlocked', false)) {
            return false;
        }

        // April 30, 2026 ke baad lock
        return now()->gt(\Carbon\Carbon::parse(self::LOCK_DATE)->endOfDay());
    }
}
