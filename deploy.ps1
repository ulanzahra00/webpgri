Set-Location d:\xampp\htdocs\pgrikotamobagu

Write-Host "Adding all changes..." -ForegroundColor Green
& git add -A

Write-Host "Committing changes..." -ForegroundColor Green
& git commit -m "Feat: Add report_date field to financial reports form and database"

Write-Host "Pushing to GitHub..." -ForegroundColor Green
& git push origin main

Write-Host "========================================" -ForegroundColor Green
Write-Host "Deploy selesai!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
