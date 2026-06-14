#!/bin/bash
cd /d/xampp/htdocs/pgrikotamobagu

echo "Status Git:"
git status

echo ""
echo "Adding all changes..."
git add -A

echo ""
echo "Committing..."
git commit -m "Update: Add Tanggal Laporan and Tanggal Setor fields to financial reports"

echo ""
echo "Pushing to GitHub..."
git push origin main -f

echo ""
echo "✓ Done! Check GitHub Actions in 1-2 minutes"
