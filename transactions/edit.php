<?php
session_start();
include "../config/db.php";
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['user_id'])) die("Belum login. <a href='../index.php'>Kembali</a>");
if (!isset($_GET['id'])) die("ID transaksi tidak ditemukan.");

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();
$trx = $stmt->get_result()->fetch_assoc();
if (!$trx) die("Transaksi tidak ditemukan.");

// Ambil akun & kategori
$accounts = $conn->query("SELECT id,name FROM accounts");
$categories = $conn->query("SELECT id,name FROM categories");

// Handle update
if($_SERVER['REQUEST_METHOD']==='POST'){
    $account_id = $_POST['account_id'];
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $desc = $_POST['description'];
    $date = $_POST['date'];
    $type = $_POST['type'];

    $stmt = $conn->prepare("UPDATE transactions SET account_id=?, category_id=?, amount=?, type=?, description=?, date=? WHERE id=? AND user_id=?");
    $stmt->bind_param("iidsssii", $account_id, $category_id, $amount, $type, $desc, $date, $id, $_SESSION['user_id']);
    $stmt->execute();

    $success = "Transaksi berhasil diupdate.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Transaksi</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="min-h-screen bg-gray-100 flex justify-center p-4">

<div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-lg">
<h2 class="text-2xl font-bold mb-4">Edit Transaksi</h2>

<?php if(isset($success)): ?>
<p class="text-green-600 mb-4 font-bold"><?= $success ?> <a href="list.php" class="underline">Lihat Transaksi</a></p>
<?php endif; ?>

<form method="POST" class="space-y-4">
    <div>
        <label class="block font-medium">Tanggal</label>
        <input type="date" name="date" value="<?= $trx['date'] ?>" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block font-medium">Nominal</label>
        <input type="number" step="0.01" name="amount" value="<?= $trx['amount'] ?>" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block font-medium">Catatan</label>
        <input type="text" name="description" value="<?= $trx['description'] ?>" class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block font-medium">Akun</label>
        <select name="account_id" required class="w-full border rounded px-3 py-2">
            <?php while($row = $accounts->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= $row['id']==$trx['account_id']?'selected':'' ?>><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div>
        <label class="block font-medium">Kategori</label>
        <select name="category_id" required class="w-full border rounded px-3 py-2">
            <?php while($row = $categories->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= $row['id']==$trx['category_id']?'selected':'' ?>><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div>
        <label class="block font-medium">Tipe</label>
        <select name="type" required class="w-full border rounded px-3 py-2">
            <option value="income" <?= $trx['type']=='income'?'selected':'' ?>>Income</option>
            <option value="expense" <?= $trx['type']=='expense'?'selected':'' ?>>Expense</option>
            <option value="transfer" <?= $trx['type']=='transfer'?'selected':'' ?>>Transfer</option>
        </select>
    </div>
    <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition">Simpan</button>
</form>

<a href="list.php" class="block mt-4 text-center text-gray-500 hover:text-gray-700">Kembali</a>
</div>
</body>
</html>

