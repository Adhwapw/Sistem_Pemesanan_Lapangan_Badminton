<?php include 'koneksi.php'; ?>

<?php
// === Proses booking ===
if (isset($_POST['booking'])) {
    $id_lapangan = $_POST['id_lapangan'];
    $tanggal = $_POST['tanggal'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $nama_pemesan = $_POST['nama_pemesan'];

    $query = "INSERT INTO booking (id_lapangan, tanggal, jam_mulai, jam_selesai, nama_pemesan, status)
              VALUES ('$id_lapangan', '$tanggal', '$jam_mulai', '$jam_selesai', '$nama_pemesan', 'booked')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Booking berhasil!'); window.location.href='booking.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal booking');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Lapangan</title>
    <link rel="stylesheet" href="style/all.css">
    <link rel="stylesheet" href="style/booking.css">
</head>
<body>
    <nav>
        <div class="logo">Gor Dewi</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php#Lapangan">Lapangan</a></li>
            <li><a href="booking.php">Booking</a></li>
            <li>Profile</li>
        </ul>
    </nav>
    <div class="container">
        <h2>Form Booking Lapangan</h2>

        <form method="POST">
            <label>Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" required>

            <label>Jam Mulai:</label>
            <select id="jam_mulai" name="jam_mulai" required>
                <option value="">-- Jam Mulai --</option>
                <?php
                for ($i = 7; $i <= 23; $i++) {
                    $jam = str_pad($i, 2, "0", STR_PAD_LEFT) . ":00";
                    echo "<option value='$jam'>$jam</option>";
                }
                ?>
            </select>

            <label>Jam Selesai:</label>
            <select id="jam_selesai" name="jam_selesai" required>
                <option value="">-- Jam Selesai --</option>
                <?php
                for ($i = 8; $i <= 24; $i++) {
                    $jam = str_pad($i, 2, "0", STR_PAD_LEFT) . ":00";
                    echo "<option value='$jam'>$jam</option>";
                }
                ?>
            </select>

            <button type="button" onclick="cekKetersediaan()">Cek Ketersediaan</button>

            <div id="hasilCek"></div>

            <label>Nama Pemesan:</label>
            <input type="text" name="nama_pemesan" required>

            <button type="submit" name="booking">Booking</button>
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
            xhr.open("POST", "cek_ketersediaan.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onload = function () {
                document.getElementById("hasilCek").innerHTML = this.responseText;
            };

            xhr.send(`tanggal=${tgl}&jam_mulai=${mulai}&jam_selesai=${selesai}`);
        }
    </script>
</body>
<footer>
        <p>By Kelompok 4</p>
    </footer>
</html>
