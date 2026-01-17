<?php
session_start();
include "../config/db.php";

date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['user_id'])) {
    die("Belum login. <a href='../index.php'>Kembali</a>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $type = $_POST['type'];
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $desc = $_POST['description'];
    $date = $_POST['date'];

    $account_id = 1;

    $stmt = $conn->prepare("
        INSERT INTO transactions (user_id, account_id, category_id, amount, type, description, date, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("iiidsss", $user_id, $account_id, $category_id, $amount, $type, $desc, $date);
    $stmt->execute();

    echo "<script>alert('Transaksi berhasil disimpan');window.location='../index.php';</script>";
    exit;
}

$incomeCats = $conn->query("SELECT id, name FROM categories WHERE type='income'");
$expenseCats = $conn->query("SELECT id, name FROM categories WHERE type='expense'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Transaksi</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(120deg, #f0f9ff 0%, #e0f2fe 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: sans-serif;
}
.card {
    background: #ffffffcc;
    backdrop-filter: blur(10px);
    border-radius: 1.5rem;
    padding: 2.5rem;
    width: 100%;
    max-width: 600px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
}
h2 {
    text-align: center;
    font-size: 2rem;
    font-weight: 700;
    color: #1e40af;
    margin-bottom: 2rem;
}
input, select {
    padding: 1rem 1.25rem;
    border-radius: 1rem;
    border: 1px solid #cbd5e1;
    margin-bottom: 1.25rem;
    font-size: 1rem;
    width: 100%;
    outline: none;
}
input:focus, select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
}
button {
    padding: 1rem 1.5rem;
    border-radius: 1rem;
    background-color: #3b82f6;
    color: white;
    font-weight: 700;
    width: 100%;
    margin-top: 1rem;
    transition: all 0.2s;
    font-size: 1.05rem;
}
button:hover {
    background-color: #2563eb;
    transform: translateY(-2px);
}
a.back {
    display: block;
    text-align: center;
    margin-top: 1rem;
    color: #1e40af;
    font-weight: 600;
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="card">
    <h2>Tambah Transaksi</h2>
    <form method="POST" class="flex flex-col">
        <label>Tanggal:</label>
        <input type="date" name="date" required>

        <label>Nominal:</label>
        <input type="number" step="0.01" name="amount" placeholder="0.00" required>

        <label>Catatan:</label>
        <input type="text" name="description" placeholder="Catatan">

        <label>Tipe:</label>
        <select name="type" id="typeSelect" required onchange="updateCategoryOptions()">
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>

        <label>Kategori:</label>
        <select name="category_id" id="categorySelect" required>
            <?php while($row = $incomeCats->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Simpan Transaksi</button>
    </form>
    <a href="../index.php" class="back">Kembali</a>
</div>

<script>
const incomeCats = <?= json_encode($conn->query("SELECT id,name FROM categories WHERE type='income'")->fetch_all(MYSQLI_ASSOC)) ?>;
const expenseCats = <?= json_encode($conn->query("SELECT id,name FROM categories WHERE type='expense'")->fetch_all(MYSQLI_ASSOC)) ?>;

function updateCategoryOptions() {
    const type = document.getElementById('typeSelect').value;
    const select = document.getElementById('categorySelect');
    select.innerHTML = '';
    const cats = type === 'income' ? incomeCats : expenseCats;
    cats.forEach(cat => {
        const opt = document.createElement('option');
        opt.value = cat.id;
        opt.textContent = cat.name;
        select.appendChild(opt);
    });
}
</script>

</body>
</html>

