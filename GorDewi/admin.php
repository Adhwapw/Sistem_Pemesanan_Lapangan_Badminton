<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - GOR Dewi</title>
    <link rel="stylesheet" href="style/all.css">
    <link rel="stylesheet" href="style/admin.css">
</head>
<body>
    <nav>
        <div class="logo">Gor Dewi</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php#Lapangan">Lapangan</a></li>
            <li><a href="admin.php">Manage</a></li>
            <li>Profile</li>
        </ul>
    </nav>

<h1>Admin Dashboard - GOR Dewi</h1>

<!-- ==================== BAGIAN 1: KELOLA LAPANGAN ==================== -->
<h2>Kelola Lapangan</h2>

<!-- Tombol Tambah Lapangan -->
<button type="button" onclick="toggleTambahForm()" class="btn tambah">Tambah Lapangan</button>

<!-- Form Tambah Lapangan -->
<form method="POST" id="formTambah" style="margin-top: 10px;">
    <input type="text" name="nama_lapangan" placeholder="Nama Lapangan" required>
    <select name="status_aktif">
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
    </select>
    <button type="submit" name="tambah_lapangan" class="btn selesai">Simpan</button>
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
    <tr id="row-<?= $l['id_lapangan'] ?>">
        <td><?= $l['nama_lapangan'] ?></td>
        <td><?= $l['status_aktif'] ? 'Aktif' : 'Nonaktif' ?></td>
        <td>
            <button type="button" class="btn edit" onclick="showEditForm(<?= $l['id_lapangan'] ?>)">Edit</button>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="id_lapangan" value="<?= $l['id_lapangan'] ?>">
                <button class="btn hapus" name="hapus_lapangan" onclick="return confirm('Yakin?')">Hapus</button>
            </form>
        </td>
    </tr>

    <!-- Baris Form Edit -->
    <tr id="edit-form-<?= $l['id_lapangan'] ?>" style="display: none;">
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
                <button class="btn selesai" name="update_lapangan">Simpan</button>
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
                <button name="batal_booking" class="btn hapus">Cancel</button>
            </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<!-- ==================== SCRIPT SHOW/HIDE ==================== -->
<script>
    function toggleTambahForm() {
        const form = document.getElementById("formTambah");
        form.style.display = form.style.display === "none" ? "flex" : "none";
    }

    function showEditForm(id) {
        document.getElementById("row-" + id).style.display = "none";
        document.getElementById("edit-form-" + id).style.display = "table-row";
    }
</script>

<!-- ==================== PHP ACTION HANDLING ==================== -->
<?php
if (isset($_POST['tambah_lapangan'])) {
    $nama = $_POST['nama_lapangan'];
    $status = $_POST['status_aktif'];
    mysqli_query($conn, "INSERT INTO lapangan (nama_lapangan, status_aktif) VALUES ('$nama', '$status')");
    echo "<meta http-equiv='refresh' content='0'>";
}

if (isset($_POST['update_lapangan'])) {
    $id = $_POST['id_lapangan'];
    $nama = $_POST['nama'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE lapangan SET nama_lapangan='$nama', status_aktif='$status' WHERE id_lapangan=$id");
    echo "<meta http-equiv='refresh' content='0'>";
}

if (isset($_POST['hapus_lapangan'])) {
    $id = $_POST['id_lapangan'];
    mysqli_query($conn, "DELETE FROM lapangan WHERE id_lapangan=$id");
    echo "<meta http-equiv='refresh' content='0'>";
}

if (isset($_POST['selesai_booking'])) {
    $id = $_POST['id_booking'];
    mysqli_query($conn, "UPDATE booking SET status='selesai' WHERE id_booking=$id");
    echo "<meta http-equiv='refresh' content='0'>";
}

if (isset($_POST['batal_booking'])) {
    $id = $_POST['id_booking'];
    mysqli_query($conn, "UPDATE booking SET status='cancelled' WHERE id_booking=$id");
    echo "<meta http-equiv='refresh' content='0'>";
}
?>
</body>
<footer>
        <p>By Kelompok 4</p>
    </footer>
</html>
