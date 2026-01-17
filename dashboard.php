<?php
session_start();
include "config/db.php";
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['user_id'])) die("Belum login. <a href='index.php'>Kembali</a>");
$user_id = $_SESSION['user_id'];

// Saldo
$res = $conn->query("SELECT SUM(CASE WHEN type='income' THEN amount WHEN type='expense' THEN -amount ELSE 0 END) as balance_today FROM transactions WHERE user_id=$user_id AND date='".date('Y-m-d')."'");
$balanceToday = $res->fetch_assoc()['balance_today'] ?? 0;

$res = $conn->query("SELECT SUM(CASE WHEN type='income' THEN amount WHEN type='expense' THEN -amount ELSE 0 END) as balance_month FROM transactions WHERE user_id=$user_id AND DATE_FORMAT(date,'%Y-%m')='".date('Y-m')."'");
$balanceMonth = $res->fetch_assoc()['balance_month'] ?? 0;

$res = $conn->query("SELECT SUM(CASE WHEN type='income' THEN amount WHEN type='expense' THEN -amount ELSE 0 END) as balance_year FROM transactions WHERE user_id=$user_id AND DATE_FORMAT(date,'%Y')='".date('Y')."'");
$balanceYear = $res->fetch_assoc()['balance_year'] ?? 0;

// Pengeluaran per kategori
$expenseCats = ['Makanan','Transport','House Hold','Kesehatan','Hiburan','Pakaian','Lainnya'];
$dataExp = [];
foreach($expenseCats as $cat){
    $res = $conn->query("SELECT SUM(t.amount) as total FROM transactions t
                         JOIN categories c ON t.category_id=c.id
                         WHERE t.user_id=$user_id AND t.type='expense' AND c.name='$cat'");
    $row = $res->fetch_assoc();
    $dataExp[] = $row['total'] ?? 0;
}

// Pendapatan per kategori
$incomeCats = ['Salary','Petty Cash','Bonus'];
$dataInc = [];
foreach($incomeCats as $cat){
    $res = $conn->query("SELECT SUM(t.amount) as total FROM transactions t
                         JOIN categories c ON t.category_id=c.id
                         WHERE t.user_id=$user_id AND t.type='income' AND c.name='$cat'");
    $row = $res->fetch_assoc();
    $dataInc[] = $row['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Finance App</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { background: radial-gradient(circle at top left, #f0f9ff, #e0f2fe); }
.card { background: linear-gradient(135deg, #ffffff 0%, #f0f4f8 100%); border-radius: 1rem; }
.card:hover { box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1),0 10px 10px -5px rgba(0,0,0,0.04); }
canvas { width: 100% !important; height: auto !important; }
.chart-container { display: flex; justify-content: space-between; gap: 20px; width: 100%; max-width: 1200px; }
.chart-box { flex: 0 0 49%; } /* setiap chart ambil 49% width → pasti berdampingan */
</style>
</head>
<body class="min-h-screen p-6 flex flex-col items-center">

<h1 class="text-4xl font-bold text-blue-600 mb-2 tracking-wide">Dashboard Keuangan</h1>
<p class="text-lg text-gray-700 mb-6">Ringkasan keuangan Anda secara real-time dengan visualisasi jelas</p>

<!-- Saldo Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 w-full max-w-6xl">
  <div class="card p-6 shadow-lg text-center">
    <h3 class="font-bold mb-2 text-gray-600">Saldo Hari Ini</h3>
    <p class="text-2xl font-semibold text-green-600">Rp <?= number_format($balanceToday,2,',','.') ?></p>
  </div>
  <div class="card p-6 shadow-lg text-center">
    <h3 class="font-bold mb-2 text-gray-600">Saldo Bulan Ini</h3>
    <p class="text-2xl font-semibold text-blue-600">Rp <?= number_format($balanceMonth,2,',','.') ?></p>
  </div>
  <div class="card p-6 shadow-lg text-center">
    <h3 class="font-bold mb-2 text-gray-600">Saldo Tahun Ini</h3>
    <p class="text-2xl font-semibold text-yellow-600">Rp <?= number_format($balanceYear,2,',','.') ?></p>
  </div>
</div>

<!-- Chart Horizontal: side by side -->
<div class="chart-container mb-6">
  <!-- Pengeluaran -->
  <div class="card p-4 shadow-lg chart-box">
    <h3 class="font-bold text-lg mb-4 text-gray-700">Pengeluaran per Kategori</h3>
    <canvas id="expenseChart"></canvas>
  </div>
  <!-- Pendapatan -->
  <div class="card p-4 shadow-lg chart-box">
    <h3 class="font-bold text-lg mb-4 text-gray-700">Pendapatan per Kategori</h3>
    <canvas id="incomeChart"></canvas>
  </div>
</div>

<a href="index.php" class="p-3 bg-gray-200 rounded-xl hover:bg-gray-300 transition font-semibold">Kembali</a>

<script>
const ctxExp = document.getElementById('expenseChart').getContext('2d');
new Chart(ctxExp, {
    type: 'pie',
    data: { labels: <?= json_encode($expenseCats) ?>, datasets: [{ data: <?= json_encode($dataExp) ?>, backgroundColor: ["#FF6384","#36A2EB","#FFCE56","#4BC0C0","#9966FF","#FF9F40","#FF9999"] }] },
    options: { responsive:true, plugins:{legend:{position:'bottom'}} }
});

const ctxInc = document.getElementById('incomeChart').getContext('2d');
new Chart(ctxInc, {
    type: 'pie',
    data: { labels: <?= json_encode($incomeCats) ?>, datasets: [{ data: <?= json_encode($dataInc) ?>, backgroundColor: ["#36A2EB","#FFCE56","#4BC0C0"] }] },
    options: { responsive:true, plugins:{legend:{position:'bottom'}} }
});
</script>

</body>
</html>

