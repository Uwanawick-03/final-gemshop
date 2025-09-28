# Gem Shop Management System - Production Environment Setup
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Gem Shop Management System" -ForegroundColor Yellow
Write-Host "Production Environment Setup" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "🧹 Cleaning up test data and creating admin users..." -ForegroundColor Green
Write-Host ""

try {
    php artisan db:seed --class=ProductionDataSeeder
    
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "✅ Setup Complete!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "🔐 Administrative Users Created:" -ForegroundColor Yellow
    Write-Host "   📧 Admin: admin@gemshop.com" -ForegroundColor White
    Write-Host "   🔑 Password: Admin@2024!" -ForegroundColor White
    Write-Host ""
    Write-Host "   📧 Manager: manager@gemshop.com" -ForegroundColor White
    Write-Host "   🔑 Password: Manager@2024!" -ForegroundColor White
    Write-Host ""
    Write-Host "⚠️  IMPORTANT: Please change these passwords after first login!" -ForegroundColor Red
    Write-Host ""
    Write-Host "🎉 Your Gem Shop Management System is ready for production use!" -ForegroundColor Green
    
} catch {
    Write-Host "❌ Error during setup: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "Press any key to continue..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
