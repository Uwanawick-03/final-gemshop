@echo off
echo ========================================
echo Gem Shop Management System
echo Production Environment Setup
echo ========================================
echo.

echo Cleaning up test data and creating admin users...
echo.

php artisan db:seed --class=ProductionDataSeeder

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Administrative Users Created:
echo   Admin: admin@gemshop.com
echo   Password: Admin@2024!
echo.
echo   Manager: manager@gemshop.com
echo   Password: Manager@2024!
echo.
echo IMPORTANT: Please change these passwords after first login!
echo.
pause
