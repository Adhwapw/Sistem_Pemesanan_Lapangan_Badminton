<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $data = mysqli_fetch_assoc($query);

    // Cek password biasa, tanpa hash
    if ($data && $password === $data['password']) {
        // Set session
        $_SESSION['id_users'] = $data['id_users']; // atau sesuaikan kolom id
        $_SESSION['user_nama'] = $data['nama_lengkap'];
        $_SESSION['user_email'] = $data['email'];
        $_SESSION['user_foto'] = $data['foto_profil'];
        $_SESSION['user_role'] = $data['role'];

        // Redirect berdasarkan role
        if ($data['role'] === 'admin') {
            header("Location: admin/admin.php");
        } else {
            header("Location: user/home.php");
        }
        exit;
    } else {
        echo "<script>alert('Email atau password salah!'); window.location='index.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="style/all.css">
    <link rel="stylesheet" href="style/login.css">
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" name="login">Masuk</button>
        </form>
        <p class="daftar-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>

</html>