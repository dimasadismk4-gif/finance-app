<?php
// config/db.php
$conn = new mysqli("localhost", "finance", "finance123", "finance_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

