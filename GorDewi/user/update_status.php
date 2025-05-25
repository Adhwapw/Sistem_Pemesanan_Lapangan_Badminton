<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_users'])) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location.href='../index.php';</script>";
    exit;
}

if (!isset($_GET['id_booking']) || !isset($_GET['status'])) {
    echo "<script>alert('Data tidak lengkap!'); window.location.href='../user/profile_user.php';</script>";
    exit;
}

$id_booking = mysqli_real_escape_string($conn, $_GET['id_booking']);
$status = $_GET['status'];

// Tentukan update pada kolom 'status' (bukan status_pembayaran)
$status_booking = '';

if ($status === 'success') {
    $status_booking = 'booked';
} else if ($status === 'pending') {
    $status_booking = 'belum_dibayar';
} else {
    echo "<script>alert('Status tidak valid!'); window.location.href='../user/profile_user.php';</script>";
    exit;
}

// Update status di database
$query = "UPDATE booking 
          SET status = '$status_booking' 
          WHERE id_booking = '$id_booking' AND id_users = '{$_SESSION['id_users']}'";

if (mysqli_query($conn, $query)) {
    echo "<script> window.location.href='../user/booking.php';</script>";
} else {
    echo "<script>alert('Gagal memperbarui status pembayaran.'); window.location.href='../user/profile_user.php';</script>";
}
?>
