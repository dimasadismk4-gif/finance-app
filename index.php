<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Finance App</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
<style>
body {
    background: radial-gradient(circle at top left, #f0f9ff, #e0f2fe);
}
.card {
    background: linear-gradient(135deg, #ffffff 0%, #f0f4f8 100%);
    border-radius: 9999px; /* ellipse / pill */
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
    padding: 1.5rem 2rem;
    min-width: 180px;
    text-align: center;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
}
.grid-menu {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 2rem; /* jarak antar tombol */
    justify-items: center;
    margin-top: 2rem;
}
.icon {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}
</style>
</head>
<body class="min-h-screen flex flex-col items-center p-6">

<h1 class="text-4xl font-bold text-blue-600 mb-2 tracking-wide">Finance App</h1>

<?php if(!isset($_SESSION['user_id'])): ?>
<h2 class="text-xl text-gray-700 mb-6">Login</h2>
<form action="auth/login.php" method="POST" class="flex flex-col gap-4 w-full max-w-sm">
    <input name="username" placeholder="Username" class="p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
    <input name="password" type="password" placeholder="Password" class="p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
    <button type="submit" class="p-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">Login</button>
</form>

<?php else: ?>
<p class="text-lg text-gray-700">Selamat datang, <span class="font-semibold"><?= $_SESSION['username'] ?></span>!</p>

<div class="grid-menu">
    <!-- Tambah Transaksi -->
    <a href="transactions/add.php" class="card">
        <div class="icon">➕</div>
        <div class="font-semibold text-gray-700">Tambah Transaksi</div>
    </a>
    <!-- Lihat Transaksi -->
    <a href="transactions/list.php" class="card">
        <div class="icon">📄</div>
        <div class="font-semibold text-gray-700">Lihat Transaksi</div>
    </a>
    <!-- Dashboard -->
    <a href="dashboard.php" class="card">
        <div class="icon">📊</div>
        <div class="font-semibold text-gray-700">Dashboard</div>
    </a>
    <!-- Logout -->
    <a href="auth/logout.php" class="card">
        <div class="icon">🚪</div>
        <div class="font-semibold text-gray-700">Logout</div>
    </a>
</div>

<?php endif; ?>
</body>
</html>

