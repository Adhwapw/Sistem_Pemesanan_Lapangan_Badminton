<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_users'])) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location.href='../index.php';</script>";
    exit;
}

$id_user = $_SESSION['id_users'];
$sqlUser = mysqli_query($conn, "SELECT * FROM users WHERE id_users = '$id_user'");
$dataUser = mysqli_fetch_assoc($sqlUser);
$nama_user = $dataUser['nama_lengkap'];
$email_user = $dataUser['email'];

if (isset($_POST['booking'])) {
    $id_lapangan = $_POST['id_lapangan'];
    $tanggal = $_POST['tanggal'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $status_pembayaran = $_POST['status_pembayaran'];

    if (strtotime($jam_selesai) <= strtotime($jam_mulai)) {
        echo "<script>alert('Jam selesai harus lebih besar dari jam mulai!'); window.history.back();</script>";
        exit;
    }

    $cek = mysqli_query($conn, "SELECT * FROM booking 
        WHERE id_lapangan = '$id_lapangan' 
        AND tanggal = '$tanggal' 
        AND (jam_mulai < '$jam_selesai' AND jam_selesai > '$jam_mulai')");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Maaf, lapangan sudah dibooking pada waktu tersebut.'); window.history.back();</script>";
        exit;
    }

    $query = "INSERT INTO booking 
        (id_lapangan, tanggal, jam_mulai, jam_selesai, id_users, status, status_pembayaran)
        VALUES 
        ('$id_lapangan', '$tanggal', '$jam_mulai', '$jam_selesai', '$id_user', 'booked', '$status_pembayaran')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Booking berhasil!'); window.location.href='../user/booking.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal booking: " . mysqli_error($conn) . "'); window.history.back();</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Booking Lapangan</title>
    <link rel="stylesheet" href="../style/all.css">
    <link rel="stylesheet" href="../style/booking.css">
</head>

<body>
    <nav>
        <div class="logo">Gor Dewi</div>
        <ul>
            <li><a href="../user/home.php">Home</a></li>
            <li><a href="../user/home.php#Lapangan">Lapangan</a></li>
            <li><a href="../user/booking.php">Booking</a></li>
            <li><a href="../user/profile_user.php">Profile</a></li>
        </ul>
    </nav>
    <div class="container">
        <h2>Form Booking Lapangan</h2>

        <form method="POST">
            <input type="hidden" name="id_lapangan" value="<?= $_GET['id_lapangan'] ?? '' ?>">

            <div class="harga-info">
                <h4>Daftar Harga</h4>
                <ul>
                    <li>08.00–15.00: <strong>Rp40.000/jam</strong></li>
                    <li>15.00–24.00: <strong>Rp50.000/jam</strong></li>
                    <li><strong>PROMO</strong>: 22.00–24.00 (2 jam) → <strong>Rp35.000/jam</strong></li>
                </ul>
            </div>

            <label>Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" required>

            <label>Jam Mulai:</label>
            <select id="jam_mulai" name="jam_mulai" required>
                <option value="">-- Jam Mulai --</option>
                <?php for ($i = 7; $i <= 23; $i++): ?>
                    <option value="<?= sprintf('%02d:00', $i) ?>"><?= sprintf('%02d:00', $i) ?></option>
                <?php endfor; ?>
            </select>

            <label>Jam Selesai:</label>
            <select id="jam_selesai" name="jam_selesai" required>
                <option value="">-- Jam Selesai --</option>
                <?php for ($i = 8; $i <= 24; $i++): ?>
                    <option value="<?= sprintf('%02d:00', $i) ?>"><?= sprintf('%02d:00', $i) ?></option>
                <?php endfor; ?>
            </select>

            <button type="button" onclick="cekKetersediaan()">Cek Ketersediaan</button>
            <div id="hasilCek" style="margin-top: 10px;"></div>

            <label>Nama Pemesan:</label>
            <p><?= htmlspecialchars($nama_user) ?></p>

            <label>Email Pemesan:</label>
            <p><?= htmlspecialchars($email_user) ?></p>

            <label>Pembayaran:</label>
            <select name="status_pembayaran" id="status_pembayaran" onchange="tampilNominal()" required>
                <option value="">-- Pilih Metode --</option>
                <option value="DP">DP 50%</option>
                <option value="LUNAS">Lunas</option>
            </select>

            <div id="nominal" style="margin: 10px 0; font-weight: bold;"></div>

            <button type="submit" name="booking">Bayar</button>
        </form>
    </div>

    <script>
        function cekKetersediaan() {
            const tgl = document.getElementById("tanggal").value;
            const mulai = document.getElementById("jam_mulai").value;
            const selesai = document.getElementById("jam_selesai").value;

            if (!tgl || !mulai || !selesai) {
                alert("Isi tanggal dan jam terlebih dahulu.");
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../cek_ketersediaan.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                document.getElementById("hasilCek").innerHTML = this.responseText;
            };

            xhr.send(`tanggal=${tgl}&jam_mulai=${mulai}&jam_selesai=${selesai}`);
        }

        function tampilNominal() {
            const mulai = document.getElementById("jam_mulai").value;
            const selesai = document.getElementById("jam_selesai").value;
            const metode = document.getElementById("status_pembayaran").value;

            if (!mulai || !selesai || !metode) return;

            const start = parseInt(mulai.split(":")[0]);
            const end = parseInt(selesai.split(":")[0]);

            if (end <= start) {
                document.getElementById("nominal").innerText = "Durasi tidak valid!";
                return;
            }

            let total = 0;
            for (let i = start; i < end; i++) {
                if (i >= 8 && i < 15) {
                    total += 40000;
                } else if (i >= 15 && i < 22) {
                    total += 50000;
                } else if (i >= 22 && i < 24) {
                    if (end - start === 2 && start === 22) {
                        total = 35000 * 2; // Promo khusus 22–24
                        break;
                    } else {
                        total += 50000;
                    }
                }
            }

            const bayar = metode === "DP" ? total * 0.5 : total;

            document.getElementById("nominal").innerText =
                `Total yang harus dibayar: Rp ${bayar.toLocaleString('id-ID')}`;
        }
    </script>
</body>
<footer>
    <p>By Kelompok 4</p>
</footer>

</html>