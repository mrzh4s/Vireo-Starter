@echo off
REM Beam Framework CLI Launcher (Windows)
REM Usage: beam <command> [arguments] [options]

setlocal enabledelayedexpansion

REM Get the directory where this script is located (root)
set "ROOT_DIR=%~dp0"

REM Try to find PHP in PATH
where php >nul 2>&1
if %errorlevel% equ 0 (
    set "PHP_BINARY=php"
    goto :run
)

REM Try common PHP installation paths
if exist "C:\php\php.exe" (
    set "PHP_BINARY=C:\php\php.exe"
    goto :run
)

if exist "C:\Program Files\PHP\php.exe" (
    set "PHP_BINARY=C:\Program Files\PHP\php.exe"
    goto :run
)

REM PHP not found
echo Error: PHP is not installed or not in PATH
echo.
echo Please install PHP 8.4+ or add it to your PATH
echo Download from: https://www.php.net/downloads
exit /b 1

:run
REM Execute the PHP CLI script
"%PHP_BINARY%" "%ROOT_DIR%beam" %*
exit /b %errorlevel%
