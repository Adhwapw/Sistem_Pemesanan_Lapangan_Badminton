<?php
session_start();
include '../koneksi.php';
require_once '../vendor/autoload.php';

// Set config Midtrans di awal
\Midtrans\Config::$serverKey = 'SB-Mid-server-OgQBd753BSBDy8TnqyIR6ImC';
\Midtrans\Config::$isProduction = false;

// Cek user sudah login
if (!isset($_SESSION['id_users'])) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location.href='../index.php';</script>";
    exit;
}

// Cek ada id_booking
if (!isset($_GET['id_booking'])) {
    echo "<script>alert('ID Booking tidak ditemukan!'); window.location.href='../user/profile_user.php';</script>";
    exit;
}

$id_booking = $_GET['id_booking'];
$id_user = $_SESSION['id_users'];

// Ambil data booking dari database
$sql = "SELECT b.*, l.nama_lapangan 
        FROM booking b 
        JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
        WHERE b.id_booking = '$id_booking' AND b.id_users = '$id_user'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    echo "<script>alert('Booking tidak ditemukan!'); window.location.href='../user/profile_user.php';</script>";
    exit;
}

$data = mysqli_fetch_assoc($result);

// Fungsi hitung total bayar
function hitungTotalBayar($mulai, $selesai, $metode) {
    $start = intval(substr($mulai, 0, 2));
    $end = intval(substr($selesai, 0, 2));
    $total = 0;
    for ($i = $start; $i < $end; $i++) {
        if ($i >= 8 && $i < 15) {
            $total += 40000;
        } else if ($i >= 15 && $i < 22) {
            $total += 50000;
        } else if ($i >= 22 && $i < 24) {
            if (($end - $start) == 2 && $start == 22) {
                $total = 35000 * 2;
                break;
            } else {
                $total += 50000;
            }
        }
    }
    return ($metode === 'DP') ? $total * 0.5 : $total;
}

$totalBayar = hitungTotalBayar($data['jam_mulai'], $data['jam_selesai'], $data['status_pembayaran']);
$nama_lapangan = $data['nama_lapangan'];

// Ambil data user
$user_result = mysqli_query($conn, "SELECT * FROM users WHERE id_users = '$id_user'");
$data_user = mysqli_fetch_assoc($user_result);

$nama_user = $data_user['nama_lengkap'];
$email_user = $data_user['email'];

$order_id = 'BOOK-' . $id_booking;

// Cek status transaksi di Midtrans (handle error jika transaksi belum ada)
try {
    /** @var \Midtrans\TransactionStatus|mixed $status */
    $status = \Midtrans\Transaction::status($order_id);

    if (is_object($status) && isset($status->transaction_status)) {
        if ($status->transaction_status === 'settlement' || $status->transaction_status === 'capture') {
            // Update status pembayaran di database jika sudah lunas
            mysqli_query($conn, "UPDATE booking SET status = 'booked' WHERE id_booking = '$id_booking'");

            echo "<script>alert('Booking ini sudah dibayar.'); window.location.href='../user/profile_user.php';</script>";
            exit;
        }
    }
} catch (Exception $e) {
    // Jika transaksi belum ada di Midtrans, lanjut ke proses pembayaran
}

// Generate snap token untuk Midtrans
$params = [
    'transaction_details' => [
        'order_id' => $order_id,
        'gross_amount' => $totalBayar,
    ],
    'customer_details' => [
        'first_name' => $nama_user,
        'email' => $email_user,
    ],
];

$snapToken = \Midtrans\Snap::getSnapToken($params);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Booking</title>
    <link rel="stylesheet" href="../style/all.css">
    <link rel="stylesheet" href="../style/payment.css">
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
    <h2>Konfirmasi Pembayaran</h2>
    <p><strong>Lapangan:</strong> <?= htmlspecialchars($nama_lapangan) ?></p>
    <p><strong>Tanggal:</strong> <?= htmlspecialchars($data['tanggal']) ?></p>
    <p><strong>Jam:</strong> <?= htmlspecialchars($data['jam_mulai']) ?> - <?= htmlspecialchars($data['jam_selesai']) ?></p>
    <p><strong>Status Pembayaran:</strong> <?= htmlspecialchars($data['status_pembayaran']) ?></p>
    <p><strong>Total Bayar:</strong> Rp <?= number_format($totalBayar, 0, ',', '.') ?></p>

    <button id="pay-button">Bayar Sekarang</button>

    <div id="payCloseModal" class="modal" style="display:none;">
        <div class="modal-content">
            <p>Kamu menutup popup tanpa menyelesaikan pembayaran. Apa kamu ingin mencoba lagi?</p>
            <div class="modal-buttons">
                <button id="retryPay" class="btn selesai">Coba Lagi</button>
                <button id="cancelPay" class="btn cancel">Batal</button>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-mnLb7ns67jijV7zg"></script>
<script>
const payCloseModal = document.getElementById('payCloseModal');
const retryPayBtn = document.getElementById('retryPay');
const cancelPayBtn = document.getElementById('cancelPay');

document.getElementById('pay-button').addEventListener('click', function () {
    snap.pay('<?= $snapToken ?>', {
        onSuccess: function (result) {
            window.location.href = "update_status.php?id_booking=<?= $id_booking ?>&status=success";
        },
        onPending: function (result) {
            window.location.href = "update_status.php?id_booking=<?= $id_booking ?>&status=belum_dibayar";
        },
        onError: function (result) {
            alert("Pembayaran gagal. Silakan coba lagi.");
            console.log(result);
        },
        onClose: function () {
            payCloseModal.style.display = 'flex';
        }
    });
});

retryPayBtn.addEventListener('click', () => {
    payCloseModal.style.display = 'none';
    document.getElementById('pay-button').click();
});

cancelPayBtn.addEventListener('click', () => {
    payCloseModal.style.display = 'none';
});
</script>
</body>
</html>
