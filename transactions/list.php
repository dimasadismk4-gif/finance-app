<?php
session_start();
include "../config/db.php";
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['user_id'])) die("Belum login. <a href='../index.php'>Kembali</a>");
$user_id = $_SESSION['user_id'];

$result = $conn->query("
    SELECT t.id,t.date,t.amount,t.type,t.description,t.created_at,
           a.name AS account, c.name AS category
    FROM transactions t
    JOIN accounts a ON t.account_id = a.id
    JOIN categories c ON t.category_id = c.id
    WHERE t.user_id=$user_id
    ORDER BY t.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Riwayat Transaksi</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="min-h-screen bg-gray-100 flex justify-center p-4">
<div class="w-full max-w-6xl">
<h2 class="text-2xl font-bold mb-4">Riwayat Transaksi</h2>

<?php if($result->num_rows>0): ?>
<table class="w-full rounded-lg shadow overflow-hidden">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2">Tanggal</th>
            <th class="px-4 py-2">Jam</th>
            <th class="px-4 py-2">Akun</th>
            <th class="px-4 py-2">Kategori</th>
            <th class="px-4 py-2">Nominal</th>
            <th class="px-4 py-2">Tipe</th>
            <th class="px-4 py-2">Catatan</th>
            <th class="px-4 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row=$result->fetch_assoc()): ?>
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-2"><?= $row['date'] ?></td>
            <td class="px-4 py-2"><?= date('H:i:s',strtotime($row['created_at'])) ?></td>
            <td class="px-4 py-2"><?= $row['account'] ?></td>
            <td class="px-4 py-2"><?= $row['category'] ?></td>
            <td class="px-4 py-2">Rp <?= number_format($row['amount'],2,',','.') ?></td>
            <td class="px-4 py-2"><?= $row['type'] ?></td>
            <td class="px-4 py-2"><?= $row['description'] ?></td>
            <td class="px-4 py-2 space-x-2">
                <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-500 hover:underline">Edit</a>
                <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin mau hapus transaksi ini?')" class="text-red-500 hover:underline">Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
<p class="text-gray-500">Belum ada transaksi.</p>
<?php endif; ?>

<a href="../index.php" class="block mt-4 text-center text-gray-500 hover:text-gray-700">Kembali</a>
</div>
</body>
</html>

