<?php
session_start();
include "../config/db.php";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Login gagal, username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Finance App</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
<style>
body {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: sans-serif;

    /* Wallpaper kartun mobil */
    background-image: url('https://i.ibb.co/6yP2kqD/cartoon-cars-wallpaper.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.card {
    background: #ffffffcc;
    backdrop-filter: blur(12px);
    border-radius: 1.5rem;
    padding: 3rem 2.5rem;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    text-align: center;
    position: relative;
}
h1 {
    font-size: 2.25rem;
    font-weight: 700;
    color: #1e40af;
    margin-bottom: 0.5rem;
}
p.sub {
    font-size: 1rem;
    color: #475569;
    margin-bottom: 2rem;
}
input {
    width: 100%;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    font-size: 1rem;
    border-radius: 0.75rem;
    border: 1px solid #cbd5e1;
    outline: none;
}
input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
}
button {
    width: 100%;
    padding: 1rem;
    border-radius: 0.75rem;
    background-color: #3b82f6;
    color: white;
    font-weight: 700;
    font-size: 1.05rem;
    transition: all 0.2s;
}
button:hover {
    background-color: #2563eb;
    transform: translateY(-2px);
}
p.error {
    color: #dc2626;
    font-weight: 600;
    margin-bottom: 1rem;
}
.icon {
    font-size: 4rem;
    position: absolute;
    top: -40px;
    left: 50%;
    transform: translateX(-50%);
    background: #3b82f6;
    color: white;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
</style>
</head>
<body>

<div class="card">
    <div class="icon">💰</div>
    <h1>Finance App</h1>
    <p class="sub">Masuk untuk mengelola keuangan Anda</p>

    <?php if(isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="flex flex-col">
        <input name="username" placeholder="Username" required>
        <input name="password" type="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <p class="sub mt-4"><a href="#" class="text-blue-600 font-semibold underline">Lupa password?</a></p>
</div>

</body>
</html>

