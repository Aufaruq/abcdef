<?php
session_start();
require_once "mysql.php"; // Menghubungkan ke database

// Query untuk mendapatkan daftar pembayaran
$sql = "SELECT * FROM pembayaran";
$result = mysqli_query($conn, $sql);
$daftar_pembayaran = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $daftar_pembayaran[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat History Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <h2>Lihat History Pembayaran</h2>

    <div class="container">
    <!-- Tampilkan daftar pembayaran -->
    <table class="table align-middle table-hover border">
        <thead>
            <tr>
                <th>ID Pembayaran</th>
                <th>ID Petugas</th>
                <th>NISN</th>
                <th>Tanggal Bayar</th>
                <th>Bulan Dibayar</th>
                <th>Tahun Dibayar</th>
                <th>ID SPP</th>
                <th>Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daftar_pembayaran as $pembayaran): ?>
            <tr>
                <td><?php echo $pembayaran['id_pembayaran']; ?></td>
                <td><?php echo $pembayaran['id_petugas']; ?></td>
                <td><?php echo $pembayaran['nisn']; ?></td>
                <td><?php echo $pembayaran['tgl_bayar']; ?></td>
                <td><?php echo $pembayaran['bulan_dibayar']; ?></td>
                <td><?php echo $pembayaran['tahun_dibayar']; ?></td>
                <td><?php echo $pembayaran['id_spp']; ?></td>
                <td><?php echo $pembayaran['jumlah_bayar']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
