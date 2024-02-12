<?php
session_start();
require_once "mysql.php"; // Menghubungkan ke database

// Fungsi untuk menghasilkan laporan dalam format CSV
function generateCSVReport($filename, $data) {
    // Atur header untuk file CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);

    // Buat file handle untuk output
    $output = fopen('php://output', 'w');

    // Tulis header untuk file CSV
    fputcsv($output, array('ID Pembayaran', 'ID Petugas', 'NISN', 'Tanggal Bayar', 'Bulan Dibayar', 'Tahun Dibayar', 'ID SPP', 'Jumlah Bayar'));

    // Tulis data pembayaran ke dalam file CSV
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    // Tutup file handle
    fclose($output);
}

// Query untuk mendapatkan data pembayaran
$sql = "SELECT * FROM pembayaran";
$result = mysqli_query($conn, $sql);
$daftar_pembayaran = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $daftar_pembayaran[] = $row;
    }
}

// Panggil fungsi untuk menghasilkan laporan dalam format CSV
generateCSVReport('laporan_pembayaran.csv', $daftar_pembayaran);
?>
