@echo off
cd /d d:\xampp\htdocs\pgrikotamobagu

echo ========================================
echo DEPLOYING TO GITHUB...
echo ========================================
echo.

echo [1/3] Staging all changes...
git add -A

echo [2/3] Committing...
git commit -m "Feat: Add Tanggal Laporan and Tanggal Setor date fields to financial reports"

echo [3/3] Pushing to GitHub (may take 1-2 minutes)...
git push origin main

echo.
echo ========================================
echo DONE! Waiting for GitHub Actions...
echo ========================================
echo.
echo Next steps:
echo 1. Wait 2-3 minutes for automatic deployment
echo 2. Open: https://pgrikotamobagu.my.id/admin/?module=finance
echo 3. Click "Tambah Laporan"
echo 4. Hard refresh with Ctrl+Shift+R
echo.
pause
