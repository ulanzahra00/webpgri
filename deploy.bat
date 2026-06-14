@echo off
cd /d d:\xampp\htdocs\pgrikotamobagu

REM Add all changes
git add -A

REM Commit dengan message yang deskriptif
git commit -m "Feat: Add report_date field to financial reports form and database"

REM Push ke GitHub
git push origin main

echo.
echo ========================================
echo Deploy selesai!
echo ========================================
pause
