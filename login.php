<?php
session_start();
require_once "mysql.php";

if (isset($_SESSION['user'])) {
    // Jika pengguna sudah login, arahkan ke dashboard
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari petugas berdasarkan username dan password
    $sql_petugas = "SELECT * FROM petugas WHERE username = ? AND password = ?";
    $stmt_petugas = mysqli_prepare($conn, $sql_petugas);
    mysqli_stmt_bind_param($stmt_petugas, "ss", $username, $password);
    mysqli_stmt_execute($stmt_petugas);
    $result_petugas = mysqli_stmt_get_result($stmt_petugas);

    // Jika petugas ditemukan
    if ($row_petugas = mysqli_fetch_assoc($result_petugas)) {
        $_SESSION['user'] = $row_petugas; // Simpan informasi petugas ke session
        header("Location: dashboard.php"); // Redirect ke dashboard
        exit;
    } else {
        $error = "Username or password is incorrect";
    }

    mysqli_stmt_close($stmt_petugas);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <div class="login container">
        <div class="card p-3 align-items-center">
            <h2 class="pb-3">Login Petugas</h2>
            <img src="img/logo.png" alt="">
            <?php if(isset($error)) echo "<p>$error</p>"; ?>
            <form action="" method="POST">
                <div class="mb-2">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br>
                </div>
                <div class="mb-2">
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Login</button>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    
</body>
</html>
