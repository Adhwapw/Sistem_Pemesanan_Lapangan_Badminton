<?php
include '../koneksi.php';

function redirectWithMessage($msg, $type = 'success')
{
    header("Location: ../admin/kelola_lapangan.php?msg=" . urlencode($msg) . "&type=" . $type);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tambah_lapangan'])) {
        $nama = $_POST['nama_lapangan'];
        $status = $_POST['status_aktif'];
        $cek = mysqli_query($conn, "SELECT * FROM lapangan WHERE nama_lapangan = '$nama'");
        if (mysqli_num_rows($cek) > 0) {
            redirectWithMessage('Nama lapangan sudah terdaftar!', 'error');
        } else {
            $insert = mysqli_query($conn, "INSERT INTO lapangan (nama_lapangan, status_aktif) VALUES ('$nama', '$status')");
            if ($insert) {
                redirectWithMessage('Lapangan berhasil ditambahkan!', 'success');
            } else {
                redirectWithMessage('Gagal menambahkan lapangan.', 'error');
            }
        }
    }

    if (isset($_POST['update_lapangan'])) {
        $id = $_POST['id_lapangan'];
        $nama = $_POST['nama_lapangan'];
        $status = $_POST['status_aktif'];
        $cek = mysqli_query($conn, "SELECT * FROM lapangan WHERE nama_lapangan = '$nama' AND id_lapangan != $id");
        if (mysqli_num_rows($cek) > 0) {
            redirectWithMessage('Nama lapangan sudah terdaftar!', 'error');
        } else {
            $update = mysqli_query($conn, "UPDATE lapangan SET nama_lapangan='$nama', status_aktif='$status' WHERE id_lapangan=$id");
            if ($update) {
                redirectWithMessage('Lapangan berhasil diupdate!', 'success');
            } else {
                redirectWithMessage('Gagal mengupdate lapangan.', 'error');
            }
        }
    }

    if (isset($_POST['hapus_lapangan'])) {
        $id = $_POST['id_lapangan'];
        $delete = mysqli_query($conn, "DELETE FROM lapangan WHERE id_lapangan=$id");
        if ($delete) {
            redirectWithMessage('Lapangan berhasil dihapus!', 'success');
        } else {
            redirectWithMessage('Gagal menghapus lapangan.', 'error');
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Kelola Lapangan - GOR Dewi</title>
    <link rel="stylesheet" href="../style/all.css">
    <link rel="stylesheet" href="../style/admin.css">
    <link rel="stylesheet" href="../style/alert.css">

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

    <form method="POST" id="formTambah" style="margin-top: 10px; display:none;">
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
                <td><?= htmlspecialchars($l['nama_lapangan']) ?></td>
                <td><?= $l['status_aktif'] ? 'Aktif' : 'Nonaktif' ?></td>
                <td>
                    <button type="button" class="btn edit" onclick="showEditForm(<?= $l['id_lapangan'] ?>)">Edit</button>
                    <button class="btn hapus" onclick="openDeleteModal(<?= $l['id_lapangan'] ?>, '<?= htmlspecialchars(addslashes($l['nama_lapangan'])) ?>')">Hapus</button>
                </td>
            </tr>

            <tr id="edit-form-<?= $l['id_lapangan'] ?>" style="display: none;">
                <form method="POST">
                    <td>
                        <input type="text" name="nama_lapangan" value="<?= htmlspecialchars($l['nama_lapangan']) ?>" required>
                        <input type="hidden" name="id_lapangan" value="<?= $l['id_lapangan'] ?>">
                    </td>
                    <td>
                        <select name="status_aktif">
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

    <!-- Modal alert sukses/gagal -->
    <div id="alertModal" class="modal">
        <div id="modalContent" class="modal-content">
            <p id="modalMessage"></p>
            <div class="modal-buttons">
                <button onclick="closeModal()" class="btn selesai">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal konfirmasi hapus -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <p id="deleteMessage"></p>
            <div class="modal-buttons">
                <form method="POST" id="deleteForm" style="margin:0;">
                    <input type="hidden" name="id_lapangan" id="deleteId">
                    <button type="submit" name="hapus_lapangan" class="btn selesai">Ya, Hapus</button>
                    <button type="button" class="btn" onclick="closeDeleteModal()" style="background-color:#ccc; color:#000;">Batal</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleTambahForm() {
            const form = document.getElementById("formTambah");
            form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
        }

        function showEditForm(id) {
            document.getElementById("row-" + id).style.display = "none";
            document.getElementById("edit-form-" + id).style.display = "table-row";
        }

        // Modal alert functions
        function showModal(message, type = 'success') {
            const modal = document.getElementById('alertModal');
            const modalContent = document.getElementById('modalContent');
            const modalMessage = document.getElementById('modalMessage');

            modalMessage.textContent = message;

            // Set class berdasarkan tipe
            modalContent.classList.remove('success', 'error');
            modalContent.classList.add(type);

            modal.classList.add('show');
        }

        function closeModal() {
            const modal = document.getElementById('alertModal');
            modal.classList.remove('show');
        }

        // Klik di luar modal-content untuk tutup modal
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('alertModal');
            if (e.target === modal) {
                closeModal();
            }
        });

        // Cek pesan dari URL dan tampilkan modal kalau ada
        window.onload = () => {
            const urlParams = new URLSearchParams(window.location.search);
            const msg = urlParams.get('msg');
            const type = urlParams.get('type') || 'success';

            if (msg) {
                showModal(msg, type);

                // Hapus query string supaya modal tidak muncul lagi pas refresh
                if (window.history.replaceState) {
                    const url = new URL(window.location);
                    url.searchParams.delete('msg');
                    url.searchParams.delete('type');
                    window.history.replaceState({}, document.title, url.pathname);
                }
            }
        }

        // Modal konfirmasi hapus
        const deleteModal = document.getElementById('deleteModal');
        const deleteMessage = document.getElementById('deleteMessage');
        const deleteIdInput = document.getElementById('deleteId');

        function openDeleteModal(id, nama) {
            deleteIdInput.value = id;
            deleteMessage.textContent = `Yakin ingin menghapus lapangan "${nama}"?`;
            deleteModal.classList.add('show');
        }

        function closeDeleteModal() {
            deleteModal.classList.remove('show');
        }

        // Klik di luar modal-content konfirmasi hapus juga tutup modal
        window.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
        });
    </script>
</body>

<footer>
    <p>By Kelompok 4</p>
</footer>

</html>
