<?php
session_start();

if (isset($_SESSION['user_id'])) { 
    header('Location: index.php'); 
    exit; 
}

require 'config.php';

$err = $ok = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = $_POST['username'];

    $p = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $u, $p);
    
    if ($stmt->execute()) {
        $ok = 'Registrasi berhasil! <a href="login.php" class="text-blue-600 font-semibold hover:underline">Login sekarang</a>';
    } else {
        $err = 'Username sudah digunakan. Silakan pilih yang lain.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Todo App</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded shadow w-80">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Daftar Akun</h1>
        
        <?php if ($err): ?>
            <p class="text-red-500 text-sm mb-4 bg-red-50 p-2 rounded border border-red-200 text-center">
                <?= htmlspecialchars($err) ?>
            </p>
        <?php endif; ?>

        <?php if ($ok): ?>
            <p class="text-green-700 text-sm mb-4 bg-green-50 p-2 rounded border border-green-200 text-center">
                <?= $ok ?>
            </p>
        <?php endif; ?>

        <form method="POST" onsubmit="return validate(this)">
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Username</label>
                <input name="username" placeholder="Minimal 3 karakter" required 
                       class="w-full border p-2 rounded" minlength="3" maxlength="50">
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Password</label>
                <input name="password" type="password" placeholder="Minimal 6 karakter" required 
                       class="w-full border p-2 rounded" minlength="6">
            </div>

            <button class="w-full bg-green-600 text-white p-2 rounded hover:bg-green-700 font-medium transition cursor-pointer">
                Daftar
            </button>
        </form>

        <p class="text-center text-sm mt-4 text-gray-600">
            Sudah punya akun? <a href="login.php" class="text-blue-600 hover:underline">Login</a>
        </p>
    </div>

    <script>
    function validate(f) {
        if (f.username.value.trim().length < 3) { 
            alert('Username minimal 3 karakter tanpa spasi berlebih'); 
            return false; 
        }
        if (f.password.value.length < 6) { 
            alert('Password minimal 6 karakter'); 
            return false; 
        }
        return true;
    }
    </script>

</body>
</html>