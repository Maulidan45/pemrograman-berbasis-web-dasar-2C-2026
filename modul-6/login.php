<?php
session_start();

if (isset($_SESSION['user_id'])) { 
    header('Location: index.php'); 
    exit; 
}

require 'config.php';

$err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = $_POST['username'];
    $p = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $u);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res && password_verify($p, $res['password'])) {
        $_SESSION['user_id']  = $res['id'];
        $_SESSION['username'] = $u;
        $_SESSION['role']     = $res['role'];
        
        header('Location: index.php'); 
        exit;
    }

    $err = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Todo App</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded shadow w-80">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Todo App</h1>
        
        <?php if ($err): ?>
            <p class="text-red-500 text-sm mb-4 bg-red-50 p-2 rounded border border-red-200 text-center">
                <?= htmlspecialchars($err) ?>
            </p>
        <?php endif; ?>

        <form method="POST" onsubmit="return validate(this)">
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Username</label>
                <input name="username" placeholder="Username" required class="w-full border p-2 rounded" minlength="3">
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Password</label>
                <input name="password" type="password" placeholder="Password" required class="w-full border p-2 rounded" minlength="6">
            </div>

            <button class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 font-medium transition cursor-pointer">Login</button>
        </form>

        <p class="text-center text-sm mt-4 text-gray-600">
            Belum punya akun? <a href="register.php" class="text-blue-600 hover:underline">Daftar</a>
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