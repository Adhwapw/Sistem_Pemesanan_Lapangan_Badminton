<?php include '../koneksi.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Kelola Booking - GOR Dewi</title>
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

    <h1>Kelola Booking</h1>
    <table>
        <tr>
            <th>Lapangan</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        $booking = mysqli_query($conn, "SELECT b.*, l.nama_lapangan FROM booking b JOIN lapangan l ON b.id_lapangan = l.id_lapangan ORDER BY tanggal DESC");
        while ($b = mysqli_fetch_assoc($booking)):
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
                    <?php elseif ($b['status'] == 'pending_cancel'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_booking" value="<?= $b['id_booking'] ?>">
                            <button name="setujui_cancel" class="btn selesai">Setujui Batal</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_booking" value="<?= $b['id_booking'] ?>">
                            <button name="tolak_cancel" class="btn hapus">Tolak</button>
                        </form>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <?php
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

    if (isset($_POST['setujui_cancel'])) {
        $id = $_POST['id_booking'];
        mysqli_query($conn, "UPDATE booking SET status='cancelled' WHERE id_booking=$id");
        echo "<meta http-equiv='refresh' content='0'>";
    }

    if (isset($_POST['tolak_cancel'])) {
        $id = $_POST['id_booking'];
        mysqli_query($conn, "UPDATE booking SET status='booked' WHERE id_booking=$id");
        echo "<meta http-equiv='refresh' content='0'>";
    }
    ?>
</body>
<footer>
    <p>By Kelompok 4</p>
</footer>

</html>
