<?php
session_start();

// Fungsi untuk mengecek apakah pengguna memiliki sesi yang valid
function isUserLoggedIn() {
    return isset($_SESSION['user']);
}

// Fungsi untuk mengecek apakah pengguna adalah admin
function isAdmin() {
    return isUserLoggedIn() && $_SESSION['user']['level'] == 'admin';
}

// Fungsi untuk mengecek apakah pengguna adalah petugas
function isPetugas() {
    return isUserLoggedIn() && $_SESSION['user']['level'] == 'petugas';
}

// Fungsi untuk mengecek apakah pengguna adalah siswa
function isSiswa() {
    return isUserLoggedIn() && $_SESSION['user']['level'] == 'siswa';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet"/>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>

                <?php if (isAdmin() || isPetugas()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Data
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="crud_siswa.php">Data Siswa</a></li>
                            <li><a href="crud_petugas.php">Data Petugas</a></li>
                            <li><a href="crud_kelas.php">Data Kelas</a></li>
                            <li><a href="crud_spp.php">Data SPP</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Transaksi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="entri_pembayaran.php">Entri Transaksi Pembayaran</a></li>
                            <li><a href="lihat_history_pembayaran.php">Lihat History Pembayaran</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                
                <?php if (isAdmin()): ?>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="generate_laporan.php">Generate Laporan</a>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
                <div class="logout justify-content-end">
                    <li></li>
                </div>
            </div>
        </div>
    </nav>


    <div class="container">
        <div class="top">
            <div class="welcome">
                <h1 class="mt-5">Selamat Datang</h1>
                <figure>
                    <blockquote class="blockquote">
                        <p>Aplikasi Pembayaran SPP</p>
                    </blockquote>
                    <figcaption class="blockquote-footer">
                        SMKS Muhammadiyah Pangkalan Bun</cite>
                    </figcaption>
                </figure>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>
</html>
