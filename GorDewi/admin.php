<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - GOR Dewi</title>
    <style>
        td.status-booked { color: blue; font-weight: bold; }
td.status-selesai { color: green; font-weight: bold; }
td.status-cancelled { color: red; font-weight: bold; }

        body { font-family: sans-serif; margin: 0; padding: 20px; }
        h2 { margin-top: 40px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; text-align: center; }
        form { margin-bottom: 20px; }
        .btn { padding: 5px 10px; text-decoration: none; border-radius: 5px; }
        .btn.edit { background-color: #2d89ef; color: white; }
        .btn.hapus { background-color: #e81123; color: white; }
        .btn.selesai { background-color: #107c10; color: white; }
    </style>
</head>
<body>

<h1>Admin Dashboard - GOR Dewi</h1>

<!-- ==================== BAGIAN 1: KELOLA LAPANGAN ==================== -->
<h2>Kelola Lapangan</h2>

<!-- Form Tambah Lapangan -->
<form method="POST">
    <input type="text" name="nama_lapangan" placeholder="Nama Lapangan" required>
    <select name="status_aktif">
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
    </select>
    <button type="submit" name="tambah_lapangan">Tambah</button>
</form>

<!-- Tabel Lapangan -->
<table>
    <tr>
        <th>Nama Lapangan</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php
    $lapangan = mysqli_query($conn, "SELECT * FROM lapangan");
    while($l = mysqli_fetch_assoc($lapangan)):
    ?>
    <tr>
        <form method="POST">
            <td>
                <input type="text" name="nama" value="<?= $l['nama_lapangan'] ?>" required>
                <input type="hidden" name="id_lapangan" value="<?= $l['id_lapangan'] ?>">
            </td>
            <td>
                <select name="status">
                    <option value="1" <?= $l['status_aktif'] ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= !$l['status_aktif'] ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </td>
            <td>
                <button class="btn edit" name="update_lapangan">Edit</button>
                <button class="btn hapus" name="hapus_lapangan" onclick="return confirm('Yakin?')">Hapus</button>
            </td>
        </form>
    </tr>
    <?php endwhile; ?>
</table>

<!-- ==================== BAGIAN 2: KELOLA BOOKING ==================== -->
<h2>Kelola Booking</h2>
<table>
    <tr>
        <th>Lapangan</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Aksi</th>
    </tr>
    <?php
    $booking = mysqli_query($conn, "SELECT b.*, l.nama_lapangan FROM booking b JOIN lapangan l ON b.id_lapangan = l.id_lapangan ORDER BY tanggal DESC");
    while($b = mysqli_fetch_assoc($booking)):
    ?>
    <tr>
        <td><?= $b['nama_lapangan'] ?></td>
        <td><?= $b['tanggal'] ?></td>
        <td><?= $b['jam_mulai'] ?> - <?= $b['jam_selesai'] ?></td>
        <td class="status-<?= $b['status'] ?>"><?= $b['status'] ?></td>
        <td>
    <?php if ($b['status'] == 'booked'): ?>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="id_booking" value="<?= $b['id_booking'] ?>">
            <button name="selesai_booking" class="btn selesai">Selesai</button>
        </form>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="id_booking" value="<?= $b['id_booking'] ?>">
            <button name="batal_booking" class="btn hapus" >Cancel</button>
        </form>
    <?php endif; ?>
</td>
    </tr>
    <?php endwhile; ?>
</table>

<!-- ==================== PHP ACTION HANDLING ==================== -->
<?php
// Tambah lapangan
if (isset($_POST['tambah_lapangan'])) {
    $nama = $_POST['nama_lapangan'];
    $status = $_POST['status_aktif'];
    mysqli_query($conn, "INSERT INTO lapangan (nama_lapangan, status_aktif) VALUES ('$nama', '$status')");
    echo "<meta http-equiv='refresh' content='0'>";
}

// Edit lapangan
if (isset($_POST['update_lapangan'])) {
    $id = $_POST['id_lapangan'];
    $nama = $_POST['nama'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE lapangan SET nama_lapangan='$nama', status_aktif='$status' WHERE id_lapangan=$id");
    echo "<meta http-equiv='refresh' content='0'>";
}

// Hapus lapangan
if (isset($_POST['hapus_lapangan'])) {
    $id = $_POST['id_lapangan'];
    mysqli_query($conn, "DELETE FROM lapangan WHERE id_lapangan=$id");
    echo "<meta http-equiv='refresh' content='0'>";
}

// Ubah status booking jadi selesai
if (isset($_POST['selesai_booking'])) {
    $id = $_POST['id_booking'];
    mysqli_query($conn, "UPDATE booking SET status='selesai' WHERE id_booking=$id");
    echo "<meta http-equiv='refresh' content='0'>";
}

// Batalkan booking
if (isset($_POST['batal_booking'])) {
    $id = $_POST['id_booking'];
    mysqli_query($conn, "UPDATE booking SET status='cancelled' WHERE id_booking=$id");
    echo "<meta http-equiv='refresh' content='0'>";
}
?>
</body>
</html>
