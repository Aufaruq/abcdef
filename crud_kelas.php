<?php
session_start();
require_once "mysql.php";

// Periksa apakah pengguna telah login sebagai admin
if (!isset($_SESSION['user']) || $_SESSION['user']['level'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Tambahkan kelas baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $nama_kelas = $_POST['nama_kelas'];
    $kompetensi_keahlian = $_POST['kompetensi_keahlian'];

    // Lakukan validasi data
    // ...

    // Simpan data kelas ke database
    $sql = "INSERT INTO kelas (nama_kelas, kompetensi_keahlian) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $nama_kelas, $kompetensi_keahlian);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: crud_kelas.php");
        exit;
    } else {
        echo "Gagal menambahkan kelas: " . mysqli_error($conn);
    }
}

// Ambil daftar kelas dari database
$sql = "SELECT * FROM kelas";
$result = mysqli_query($conn, $sql);

// Jika query berhasil dijalankan
if ($result && mysqli_num_rows($result) > 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CRUD Kelas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet"/>
        <style>
            a {
                text-decoration: none;
            }
        </style>
    </head>
    <body>
    <nav class="navbar bg-body-tertiary mb-2">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Data Kelas</a>
        </div>
    </nav>
        <div class="container">
        <h3>Tambah Kelas Baru</h3>
        <form action="" method="POST">
            <div class="mb-2 row">
                <label class="col-sm-2 col-form-label" for="nama_kelas">Nama Kelas</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" id="nama_kelas" name="nama_kelas" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="kompetensi_keahlian">Kompetensi Keahlian</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" id="kompetensi_keahlian" name="kompetensi_keahlian" required>
                </div>
            </div>
            <button class="btn btn-primary" type="submit" name="submit">Tambah</button>
        </form>
        <h3 class="mt-5" >Daftar Kelas</h3>
        <table class="table align-middle table-hover" border="1">
            <tr>
                <th>ID Kelas</th>
                <th>Nama Kelas</th>
                <th>Kompetensi Keahlian</th>
                <th>Aksi</th>
            </tr>
            <?php
            // Tampilkan data kelas dalam bentuk tabel
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $row['id_kelas']; ?></td>
                    <td><?php echo $row['nama_kelas']; ?></td>
                    <td><?php echo $row['kompetensi_keahlian']; ?></td>
                    <td>
                        <a class="btn btn-success" href="edit_kelas.php?id=<?php echo $row['id_kelas']; ?>"><i class="ri-pencil-line"></i></a>
                        <a class="btn btn-danger" href="delete_kelas.php?id=<?php echo $row['id_kelas']; ?>"><i class="ri-delete-bin-line"></i></a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    </body>
    </html>
    <?php
} else {
    echo "Tidak ada data kelas yang tersedia.";
}
?>
