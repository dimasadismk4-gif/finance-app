<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    die("Belum login. <a href='../index.php'>Kembali</a>");
}

if (!isset($_GET['id'])) die("ID transaksi tidak ditemukan.");

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);

if ($stmt->execute()) {
    echo "Transaksi berhasil dihapus. <a href='list.php'>Kembali ke Riwayat</a>";
} else {
    echo "Gagal hapus: " . $stmt->error;
}
?>

