<?php
include 'koneksi.php';

$tanggal = $_POST['tanggal'];
$jam_mulai = $_POST['jam_mulai'];
$jam_selesai = $_POST['jam_selesai'];

$lapangan = mysqli_query($conn, "SELECT * FROM lapangan WHERE status_aktif = 1");

echo "<label>Pilih Lapangan:</label><br>";
echo "<select name='id_lapangan' required>";

while ($l = mysqli_fetch_assoc($lapangan)) {
    $id = $l['id_lapangan'];

    // Cek apakah waktu tabrakan
    $cek = mysqli_query($conn, "
        SELECT * FROM booking
        WHERE id_lapangan = $id
        AND tanggal = '$tanggal'
        AND (
            (jam_mulai < '$jam_selesai' AND jam_selesai > '$jam_mulai')
        )
    ");

    $tersedia = mysqli_num_rows($cek) === 0;

    $style = $tersedia ? "enabled" : "disabled";
    $disabled = $tersedia ? "" : "disabled";

    echo "<option class='$style' value='$id' $disabled>{$l['nama_lapangan']} " . ($tersedia ? "(Tersedia)" : "(Terbooking)") . "</option>";
}

echo "</select>";
?>
