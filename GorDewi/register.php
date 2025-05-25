<?php
include 'koneksi.php';

// Variabel untuk pesan modal
$msg = '';
$type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek apakah email sudah digunakan
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $msg = 'Email sudah digunakan';
        $type = 'error';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = mysqli_query($conn, "INSERT INTO users (nama_lengkap, email, password) VALUES ('$nama', '$email', '$hashedPassword')");
        if ($query) {
            $msg = 'Registrasi berhasil! Klik tombol di bawah untuk login.';
            $type = 'success';
        } else {
            $msg = 'Registrasi gagal. Silakan coba lagi.';
            $type = 'error';
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
    <link rel="stylesheet" href="style/reg_alert.css">
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
        <p class="login-link">Sudah punya akun? <a href="index.php">Login di sini</a></p>
    </div>

    <!-- Modal -->
    <div id="alertModal" class="modal">
        <div id="modalContent" class="modal-content">
            <p id="modalMessage"></p>
            <button id="btnModal" class="btn-close">Login</button>
        </div>
    </div>

    <script>
        const modal = document.getElementById('alertModal');
        const modalContent = document.getElementById('modalContent');
        const modalMessage = document.getElementById('modalMessage');
        const btnModal = document.getElementById('btnModal');

        function showModal(message, type) {
            modalMessage.textContent = message;
            modalContent.classList.add(type);
            modal.classList.add('show');
        }

        btnModal.addEventListener('click', function () {
            window.location.href = "index.php";
        });

        // Show modal if PHP set msg
        window.onload = function () {
            const msg = <?= json_encode($msg) ?>;
            const type = <?= json_encode($type) ?>;
            if (msg !== '') {
                showModal(msg, type);
            }
        };
    </script>
</body>
</html>
