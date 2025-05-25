<?php
session_start();
include 'koneksi.php';

$loginError = false;

// Cek error dari session
if (isset($_SESSION['login_error'])) {
    $loginError = true;
    unset($_SESSION['login_error']);
}

// Proses login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $data = mysqli_fetch_assoc($query);

    if ($data && password_verify($password, $data['password'])) {
        $_SESSION['id_users'] = $data['id_users'];
        $_SESSION['user_nama'] = $data['nama_lengkap'];
        $_SESSION['user_email'] = $data['email'];
        $_SESSION['user_foto'] = $data['foto_profil'];
        $_SESSION['user_role'] = $data['role'];

        header("Location: " . ($data['role'] === 'admin' ? "admin/admin.php" : "user/home.php"));
        exit;
    } else {
        $_SESSION['login_error'] = true;
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="style/all.css">
    <link rel="stylesheet" href="style/login.css">

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

    <!-- Modal Alert -->
    <div id="custom-alert" class="<?= $loginError ? 'show' : '' ?>">
        <div class="custom-alert-box">
            <p>Email atau password salah!</p>
            <button id="alert-ok">Tutup</button>
        </div>
    </div>

    <script>
        document.getElementById('alert-ok')?.addEventListener('click', function () {
            document.getElementById('custom-alert').classList.remove('show');
        });
    </script>
</body>

</html>
