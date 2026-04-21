#!/bin/bash
# ============================================================
# Sandhya ERP — Production Deployment Script
# Changes: spatie/laravel-permission setup + Roles & Permissions
# Run this on the production server inside the project root
# ============================================================

set -e  # Exit immediately on any error

echo ""
echo "======================================================"
echo "  Sandhya ERP — Production Update"
echo "======================================================"
echo ""

# ---------- 1. Pull latest code ----------
echo "[1/7] Pulling latest code from git..."
git pull origin main
echo "      Done."

# ---------- 2. Install/update composer dependencies ----------
echo ""
echo "[2/7] Installing composer dependencies..."
composer install --no-dev --optimize-autoloader
echo "      Done."

# ---------- 3. Run database migrations ----------
echo ""
echo "[3/7] Running migrations (creates permission tables)..."
php artisan migrate --force
echo "      Done."

# ---------- 4. Seed Roles & Permissions ----------
echo ""
echo "[4/7] Seeding roles and permissions..."
php artisan db:seed --class=RolesAndPermissionsSeeder --force
echo "      Done."

# ---------- 5. Clear & re-cache everything ----------
echo ""
echo "[5/7] Clearing old cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "      Done."

echo ""
echo "[6/7] Re-caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "      Done."

# ---------- 6. Re-discover packages ----------
echo ""
echo "[7/7] Discovering packages..."
php artisan package:discover
echo "      Done."

echo ""
echo "======================================================"
echo "  Deployment complete!"
echo ""
echo "  IMPORTANT — Manual step required:"
echo "  Assign 'admin' role to your admin user:"
echo ""
echo "  php artisan tinker --execute=\"\\"
echo "    App\Models\User::where('email','YOUR_ADMIN_EMAIL')\\"
echo "    ->first()->assignRole('admin');\""
echo ""
echo "======================================================"
