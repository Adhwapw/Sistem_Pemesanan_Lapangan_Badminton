<?php
session_start();
include '../koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gor Dewi</title>
    <link rel="stylesheet" href="../style/all.css">
    <link rel="stylesheet" href="../style/home.css">
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

    <section class="hero">
        <img src="../assets/home-page.jpg" alt="Background" class="hero-bg">
        <div class="hero-content">
            <h1>Selamat Datang</h1>
            <h3>Admin Gor Dewi</h3>
        </div>
    </section>

    <section class="lapangan-section" id="Lapangan">
        <h2>Daftar Lapangan</h2>
        <div class="lapangan-container">
            <?php
            $data = mysqli_query($conn, "SELECT * FROM lapangan");
            while($d = mysqli_fetch_array($data)) {
                $status_lapangan = $d['status_aktif'] == 1 ? 'Aktif' : 'Tidak Aktif';
                echo "
                <div class='lapangan-card'>
                    <img src='../assets/lapangan_badminton.jpg' alt='Lapangan'>
                    <div class='nama'>{$d['nama_lapangan']}</div>
                    <div class='status {$status_lapangan}'>{$status_lapangan}</div>
                </div>
                ";
            }
            ?>
        </div>
    </section>

    <footer>
        <p>By Kelompok 4</p>
    </footer>
</body>
</html>
