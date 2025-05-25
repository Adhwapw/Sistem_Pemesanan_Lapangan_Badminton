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

// Handle pembatalan booking
if (isset($_POST['ajukan_cancel'])) {
    $id_booking = $_POST['id_booking'];
    mysqli_query($conn, "UPDATE booking SET status='menunggu_pembatalan' WHERE id_booking = $id_booking");
    echo "<meta http-equiv='refresh' content='0'>";
}

// Ambil data booking user
$riwayat = [];
if ($_SESSION['user_role'] === 'user') {
    $query = mysqli_query($conn, "
    SELECT booking.*, lapangan.nama_lapangan 
    FROM booking
    JOIN lapangan ON booking.id_lapangan = lapangan.id_lapangan
    WHERE booking.id_users = $id_users
    ORDER BY booking.tanggal DESC");
    while ($row = mysqli_fetch_assoc($query)) {
        $riwayat[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Profil</title>
    <link rel="stylesheet" href="../style/all.css">
    <link rel="stylesheet" href="../style/profile.css">
</head>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Modal pembatalan
        const cancelModal = document.getElementById('cancelModal');
        const confirmCancelBtn = document.getElementById('confirmCancel');
        const cancelCancelBtn = document.getElementById('cancelCancel');
        cancelModal.style.display = 'none';

        let currentForm = null;

        // Event klik tombol ajukan pembatalan
        document.querySelectorAll('.ajukan_cancel').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                currentForm = btn.closest('form'); // ambil form terkait tombol itu
                cancelModal.style.display = 'flex'; // tampilkan modal
            });
        });

        cancelCancelBtn.addEventListener('click', () => {
            cancelModal.style.display = 'none'; // tutup modal
            currentForm = null;
        });

        confirmCancelBtn.addEventListener('click', () => {
            if (currentForm) {
                currentForm.submit(); // submit form jika sudah konfirmasi
            }
        });
    });
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

        // klik di luar modal-content tutup modal
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>

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

    <div class="profile-container">
        <img src="<?= $user['foto_profil'] ?>" class="profile-photo" alt="Foto Profil">
        <h2><?= $user['nama_lengkap'] ?></h2>
        <p><?= $user['email'] ?></p>
        <a href="../logout.php" class="btn logout-btn">Logout</a>
    </div>

    <?php if ($_SESSION['user_role'] === 'user') : ?>
        <div class="booking-history">
            <h3>Riwayat Booking</h3>
            <?php if (count($riwayat) > 0) : ?>
                <table>
                    <tr>
                        <th>Tanggal</th>
                        <th>Lapangan</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <?php foreach ($riwayat as $item) : ?>
                        <tr>
                            <td><?= $item['tanggal'] ?></td>
                            <td><?= $item['nama_lapangan'] ?></td>
                            <td><?= $item['jam_mulai'] ?> - <?= $item['jam_selesai'] ?></td>
                            <td><?= $item['status'] ?></td>
                            <td>
                                <?php if ($item['status'] === 'booked') : ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_booking" value="<?= $item['id_booking'] ?>">
                                        <input type="hidden" name="ajukan_cancel" value="1">
                                        <button type="button" class="btn cancel ajukan_cancel">Ajukan Pembatalan</button>
                                    </form>
                                <?php elseif ($item['status'] === 'menunggu_pembatalan') : ?>
                                    <em>Menunggu konfirmasi admin</em>
                                <?php elseif ($item['status'] === 'belum_dibayar') : ?>
                                    <a href="../user/pembayaran.php?id_booking=<?= $item['id_booking'] ?>" class="btn bayar">Bayar</a>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else : ?>
                <p>Belum ada riwayat booking.</p>
            <?php endif; ?>
        </div>
        <!-- Modal Konfirmasi Pembatalan -->
        <div id="cancelModal" class="modal">
            <div class="modal-content">
                <p>Yakin ingin mengajukan pembatalan booking ini?</p>
                <div class="modal-buttons">
                    <button id="confirmCancel" class="btn selesai">Ya, Ajukan</button>
                    <button id="cancelCancel" class="btn cancel">Batal</button>
                </div>
            </div>
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
    <?php endif; ?>
</body>

</html>