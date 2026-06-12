# AI Typing Progress

Extension VS Code lokal untuk membuka terminal prompt dan panel kanan yang menampilkan progres perubahan kode dengan animasi mengetik.

## Cara menjalankan

1. Buka folder `vscode-ai-typing-extension` di VS Code.
2. Tekan `F5` untuk menjalankan Extension Development Host.
3. Di jendela VS Code baru, buka Command Palette.
4. Jalankan `AI Typing Progress: Open Prompt Terminal`.
5. Ketik prompt di terminal `AI Prompt`, lalu tekan Enter.

Panel kanan akan menampilkan kode berjalan seperti sedang diketik. Jika ada file aktif, extension mengambil cuplikan file tersebut sebagai konteks preview.

## Perintah terminal

- `/help` menampilkan bantuan singkat.
- `/clear` membersihkan panel kanan.
- `/speed 1-5` mengatur kecepatan animasi typing.
- `/stop` atau `Ctrl+C` menghentikan animasi yang sedang berjalan.

## Catatan

Versi ini adalah simulasi lokal untuk tampilan progres. Belum menghubungkan prompt ke AI asli dan belum mengubah file secara otomatis.
