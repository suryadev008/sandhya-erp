@echo off
setlocal
title Sandhya ERP Manager
color 0A
cls

:: ================================================================
::   CONFIGURATION — Edit this section if you change your system
:: ================================================================

:: Path where XAMPP is installed
set XAMPP_PATH=d:\XAMP

:: Path where the ERP project is located
set ERP_PATH=d:\XAMP\htdocs\sandhya_erp

:: Path to PHP executable inside XAMPP
set PHP_EXE=d:\XAMP\php\php.exe

:: Port number for the ERP (default: 8000)
set ERP_PORT=8000

:: URL to open in browser
set ERP_URL=http://127.0.0.1:%ERP_PORT%

:: ================================================================
::   DO NOT EDIT BELOW THIS LINE
:: ================================================================

:MENU
cls
echo.
echo  ==================================================
echo       SANDHYA ERP  -  MANAGER
echo  ==================================================
echo.
echo   ERP Path : %ERP_PATH%
echo   PHP      : %PHP_EXE%
echo   URL      : %ERP_URL%
echo.
echo  --------------------------------------------------
echo   [1]  Start ERP
echo   [2]  Stop ERP
echo   [0]  Exit
echo  --------------------------------------------------
echo.
set /p CHOICE="  Choose an option (1/2/0): "

if "%CHOICE%"=="1" goto START
if "%CHOICE%"=="2" goto STOP
if "%CHOICE%"=="0" goto EXIT
echo  Invalid option. Try again.
timeout /t 2 /nobreak >nul
goto MENU


:: ================================================================
:START
cls
echo.
echo  ==================================================
echo       STARTING SANDHYA ERP...
echo  ==================================================
echo.

net session >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo  [!] Admin rights required to start Apache.
    echo      Close this, right-click the file and choose
    echo      "Run as administrator"
    echo.
    pause
    goto MENU
)

:: ── Step 1: Apache ──────────────────────────────────────────────
echo  [1/3] Checking Apache...
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I "httpd.exe" >NUL
if %ERRORLEVEL% EQU 0 (
    echo        Apache already running.  OK
) else (
    echo        Starting Apache...
    start "" /B "%XAMPP_PATH%\apache_start.bat"
    timeout /t 3 /nobreak >nul
    echo        Apache started.  OK
)
echo.

:: ── Step 2: MySQL check ─────────────────────────────────────────
echo  [2/3] Checking MySQL...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I "mysqld.exe" >NUL
if %ERRORLEVEL% EQU 0 (
    echo        MySQL is running.  OK
) else (
    echo  [!]   MySQL is NOT running!
    echo        Please start MySQL from MySQL Workbench
    echo        before using the ERP.
    echo.
    echo        Press any key to continue anyway...
    pause >nul
)
echo.

:: ── Step 3: PHP Artisan ─────────────────────────────────────────
echo  [3/3] Starting ERP Application Server...
powershell -Command "try { (New-Object Net.WebClient).DownloadString('%ERP_URL%') | Out-Null; exit 0 } catch { exit 1 }" >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo        ERP Server already running.  OK
    goto OPEN_BROWSER
)

start "[ Sandhya ERP Server - Do Not Close ]" cmd /k ^
    "color 0B && echo. && echo  ======================================= && echo   Sandhya ERP Server - DO NOT CLOSE && echo   URL: %ERP_URL% && echo  ======================================= && echo. && cd /d %ERP_PATH% && %PHP_EXE% artisan serve --host=127.0.0.1 --port=%ERP_PORT%"
echo.

echo  Waiting for server to be ready...
set TRIES=0
:WAIT_LOOP
set /a TRIES+=1
if %TRIES% GTR 30 (
    echo  [!] Server took too long. Open manually: %ERP_URL%
    goto OPEN_BROWSER
)
powershell -Command "try { (New-Object Net.WebClient).DownloadString('%ERP_URL%') | Out-Null; exit 0 } catch { exit 1 }" >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    timeout /t 1 /nobreak >nul
    goto WAIT_LOOP
)

:OPEN_BROWSER
echo  Server is ready!
echo.
echo  Opening ERP in browser...
start "" "%ERP_URL%"
echo.

color 0A
echo  ==================================================
echo       ERP IS READY!  >>  %ERP_URL%
echo  ==================================================
echo.
echo  NOTE: Do NOT close the blue "ERP Server" window.
echo.
pause
goto MENU


:: ================================================================
:STOP
cls
echo.
echo  ==================================================
echo       STOPPING SANDHYA ERP...
echo  ==================================================
echo.

net session >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo  [!] Admin rights required to stop Apache.
    echo      Right-click and choose "Run as administrator"
    echo.
    pause
    goto MENU
)

echo  Stopping PHP Artisan Server...
for /f "tokens=2" %%A in ('tasklist /FI "WINDOWTITLE eq [ Sandhya ERP Server*" /FO LIST 2^>nul ^| find "PID:"') do (
    taskkill /PID %%A /F >nul 2>&1
)
taskkill /FI "WINDOWTITLE eq [ Sandhya ERP Server*" /F >nul 2>&1
echo  Done.

echo  Stopping Apache...
call "%XAMPP_PATH%\apache_stop.bat" >nul 2>&1
echo  Done.

echo.
color 0C
echo  ==================================================
echo       ERP STOPPED SUCCESSFULLY.
echo  ==================================================
color 0A
echo.
pause
goto MENU


:: ================================================================
:EXIT
echo.
echo  Goodbye!
timeout /t 2 /nobreak >nul
endlocal
exit /b
