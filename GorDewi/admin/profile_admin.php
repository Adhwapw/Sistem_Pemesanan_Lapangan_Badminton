<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: index.php");
    exit;
}

$id_users = $_SESSION['id_users'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id_users = $id_users");
$user = mysqli_fetch_assoc($result);

// Handle tambah admin
if (isset($_POST['tambah_admin'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email sudah terdaftar!');</script>";
    } else {
        mysqli_query($conn, "INSERT INTO users (nama_lengkap, email, password, role) VALUES ('$nama', '$email', '$password', 'admin')");
        echo "<script>alert('Admin berhasil ditambahkan!'); window.location='profile_admin.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Profil Admin</title>
    <link rel="stylesheet" href="../style/all.css">
    <link rel="stylesheet" href="../style/profile.css">
    <link rel="stylesheet" href="../style/tambah_admin.css">
    <script>
        function toggleAdminForm() {
            const form = document.querySelector('.admin-box');
            form.classList.toggle('show');
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            const logoutBtn = document.querySelector('.logout-btn');
            const modal = document.getElementById('logoutModal');
            const confirmLogout = document.getElementById('confirmLogout');
            const cancelLogout = document.getElementById('cancelLogout');
            modal.style.display = 'none';

            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault(); // cegah langsung logout
                modal.style.display = 'flex'; // tampilkan modal
            });

            cancelLogout.addEventListener('click', () => {
                modal.style.display = 'none'; // tutup modal
            });

            confirmLogout.addEventListener('click', () => {
                window.location.href = logoutBtn.href; // lanjut logout
            });

            // Optional: klik di luar modal-content tutup modal
            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>

</head>

<body>

    <nav>
        <div class="logo">Gor Dewi</div>
        <ul>
            <li><a href="../admin/admin.php">Home</a></li>
            <li><a href="../admin/kelola_lapangan.php">Lapangan</a></li>
            <li><a href="../admin/kelola_booking.php">Booking</a></li>
            <li><a href="../admin/profile_admin.php">Profile</a></li>
        </ul>
    </nav>

    <!-- Card Profil Admin -->
    <div class="profile-container">
        <img src="<?= $user['foto_profil'] ?>" class="profile-photo" alt="Foto Profil">
        <h2>Admin</h2>
        <h2><?= $user['nama_lengkap'] ?></h2>
        <p><?= $user['email'] ?></p>
        <div class="btn-group">
            <a href="../logout.php" class="btn logout-btn">Logout</a>
            <button class="btn selesai" onclick="toggleAdminForm()">Tambah Admin</button>
        </div>
    </div>

    <!-- Box Tambah Admin (Hidden by Default) -->
    <div class="admin-box" id="formTambahAdmin">
        <h3>Tambah Admin Baru</h3>
        <form method="POST">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="tambah_admin" class="btn selesai">Simpan</button>
        </form>
    </div>

    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <p>Yakin ingin logout?</p>
            <div class="modal-buttons">
                <button id="confirmLogout" class="btn selesai">Ya, Logout</button>
                <button id="cancelLogout" class="btn cancel">Batal</button>
            </div>
        </div>
    </div>

</body>

</html>