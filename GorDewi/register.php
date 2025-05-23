<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek apakah email sudah digunakan
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email sudah digunakan');</script>";
    } else {
        $query = mysqli_query($conn, "INSERT INTO users (nama_lengkap, email, password) VALUES ('$nama', '$email', '$password')");
        if ($query) {
            echo "<script>alert('Registrasi berhasil! Silakan login'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style/all.css">
    <link rel="stylesheet" href="style/register.css">
</head>
<body>
    <div class="register-container">
        <h2>Daftar Akun</h2>
        <form method="POST">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" name="register">Daftar</button>
        </form>
        <p class="login-link">Sudah punya akun? <a href="../login.php">Login di sini</a></p>
    </div>
</body>
</html>
