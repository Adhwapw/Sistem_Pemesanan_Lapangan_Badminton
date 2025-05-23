<?php include '../koneksi.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Kelola Lapangan - GOR Dewi</title>
    <link rel="stylesheet" href="../style/all.css">
    <link rel="stylesheet" href="../style/admin.css">
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

    <h1>Kelola Lapangan</h1>
    <button type="button" onclick="toggleTambahForm()" class="btn tambah">Tambah Lapangan</button>

    <form method="POST" id="formTambah" style="margin-top: 10px;">
        <input type="text" name="nama_lapangan" placeholder="Nama Lapangan" required>
        <select name="status_aktif">
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
        </select>
        <button type="submit" name="tambah_lapangan" class="btn selesai">Simpan</button>
    </form>

    <table>
        <tr>
            <th>Nama Lapangan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        $lapangan = mysqli_query($conn, "SELECT * FROM lapangan");
        while ($l = mysqli_fetch_assoc($lapangan)):
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

    <?php
    if (isset($_POST['tambah_lapangan'])) {
        $nama = $_POST['nama_lapangan'];
        $status = $_POST['status_aktif'];
        $cek = mysqli_query($conn, "SELECT * FROM lapangan WHERE nama_lapangan = '$nama'");
        if (mysqli_num_rows($cek) > 0) {
            echo "<script>alert('Nama lapangan sudah terdaftar!'); window.location='kelola_lapangan.php';</script>";
        } else {
            mysqli_query($conn, "INSERT INTO lapangan (nama_lapangan, status_aktif) VALUES ('$nama', '$status')");
            echo "<meta http-equiv='refresh' content='0'>";
        }
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
    ?>
</body>
<footer>
    <p>By Kelompok 4</p>
</footer>

</html>
