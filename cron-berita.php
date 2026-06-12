<?php
// ==========================================
// PENGATURAN UTAMA
// ==========================================
$token_fonnte = "4gfNgEi5xW8arTSqDevp"; 

// Daftar ID grup WhatsApp target pengiriman berita Anda
$link_grup = [
    "120363162294510671@g.us",       // Grup SDN 1 Molinow

];

// KONEKSI DATABASE WEBSITE
$db_host = "localhost";
$db_user = "pgrikota_pgri";     
$db_pass = "tanyap4ZIL"; 
$db_name = "pgrikota_pgri"; 

// ==========================================
// 1. AMBIL BERITA TERBARU DARI DATABASE
// ==========================================
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Mengambil kolom id, title, dan slug dari berita terbaru yang sudah dipublish
$sql = "SELECT id, title, slug FROM posts WHERE status = 'published' ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $judul_berita = $row['title'];
    
    // FORMAT LINK WEBSITE ANDA: /berita/slug-judul-id
    $link_berita  = "https://pgrikotamobagu.my.id/berita/" . $row['slug'] . "-" . $row['id']; 
} else {
    die("Tidak ada data berita dengan status published.");
}
$conn->close();

// ==========================================
// 2. CEK LOG (AGAR TIDAK SPAM BERITA YANG SAMA)
// ==========================================
$file_log = "terakhir_dikirim.txt";
$berita_terakhir = file_exists($file_log) ? file_get_contents($file_log) : "";

if ($link_berita === $berita_terakhir) {
    echo "Tidak ada berita baru hari ini. Pesan tidak dikirim ke grup.";
    exit;
}

// ==========================================
// 3. SUSUN FORMAT PESAN WHATSAPP
// ==========================================
$pesan  = "📰 *BERITA TERBARU HARI INI* 📰\n\n";
$pesan .= "$judul_berita\n\n";
$pesan .= "Baca selengkapnya di sini:\n$link_berita";

// ==========================================
// 4. KIRIM KE BEBERAPA GRUP WHATSAPP VIA FONNTE
// ==========================================
$response = "";

foreach ($link_grup as $target_grup) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.fonnte.com/send',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => array(
        'target' => $target_grup,
        'message' => $pesan,
      ),
      CURLOPT_HTTPHEADER => array(
        "Authorization: $token_fonnte"
      ),
    ));

    $res = curl_exec($curl);
    curl_close($curl);
    
    // Mencatat semua hasil respon balik dari server Fonnte
    $response .= "<br>Grup ($target_grup): " . $res;
}

// ==========================================
// 5. UPDATE LOG BERITA TERKINI
// ==========================================
file_put_contents($file_log, $link_berita);

echo "Proses selesai. Respon API Fonnte: " . $response;
?>