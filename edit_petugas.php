<?php
session_start();
require_once "mysql.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['level'] != 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $id_petugas = $_POST['id_petugas'];
    $username = $_POST['username'];
    $level = $_POST['level'];

    $sql = "UPDATE petugas SET username=?, level=? WHERE id_petugas=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $username, $level, $id_petugas);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: crud_petugas.php");
        exit;
    } else {
        echo "Gagal mengupdate petugas: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Ambil data petugas berdasarkan ID
if (isset($_GET['id'])) {
    $id_petugas = $_GET['id'];
    $sql = "SELECT * FROM petugas WHERE id_petugas=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_petugas);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $petugas = mysqli_fetch_assoc($result);
} else {
    header("Location: crud_petugas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Petugas</title>
</head>
<body>
    <h2>Edit Petugas</h2>
    <form action="" method="POST">
        <input type="hidden" name="id_petugas" value="<?php echo $petugas['id_petugas']; ?>">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?php echo $petugas['username']; ?>" required><br>
        <label for="level">Level:</label><br>
        <select name="level" id="level" required>
            <option value="admin" <?php echo ($petugas['level'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="petugas" <?php echo ($petugas['level'] == 'petugas') ? 'selected' : ''; ?>>Petugas</option>
        </select><br>
        <button type="submit" name="submit">Update</button>
    </form>
</body>
</html>
