<?php
require 'auth.php';
require 'config.php';

if ($_SESSION['role'] != 'admin') { 
    header('Location: index.php'); 
    exit; 
}

if (isset($_GET['role']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $r  = $_GET['role'] == 'admin' ? 'admin' : 'user';
    
    $q  = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $q->bind_param("si", $r, $id);
    $q->execute();
    
    header('Location: users.php'); 
    exit;
}

if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];

    if ($id != $_SESSION['user_id']) {
        $q = $conn->prepare("DELETE FROM users WHERE id = ?");
        $q->bind_param("i", $id);
        $q->execute();
    }
    
    header('Location: users.php'); 
    exit;
}

$users = $conn->query("SELECT id, username, role, created_at FROM users ORDER BY id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Todo App</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-blue-600 text-white p-4 flex justify-between items-center">
        <div>
            <a href="index.php" class="hover:underline">← Kembali</a>
            <span class="ml-4 font-bold">Kelola User</span>
        </div>
        <span class="text-xs bg-blue-800 px-2 py-1 rounded">Admin Panel</span>
    </nav>

    <div class="max-w-3xl mx-auto mt-6 px-4">
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-100 text-gray-700 font-semibold uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="p-4 text-left">Username</th>
                        <th class="p-4 text-left">Role</th>
                        <th class="p-4 text-left">Tanggal Dibuat</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while ($u = $users->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 font-medium text-gray-950"><?= htmlspecialchars($u['username']) ?></td>
                            <td class="p-4">
                                <?php if ($u['role'] == 'admin'): ?>
                                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-semibold">Admin</span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">User</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-gray-500"><?= htmlspecialchars($u['created_at']) ?></td>
                            <td class="p-4 text-center">
                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                    <?php $newrole = ($u['role'] == 'admin') ? 'user' : 'admin'; ?>
                                    
                                    <div class="flex items-center justify-center gap-3">
                                      
                                        
                                        <a href="?del=<?= $u['id'] ?>" 
                                           onclick="return confirm('Hapus user <?= htmlspecialchars($u['username']) ?>?')"
                                           class="text-sm text-red-600 hover:text-red-800 hover:underline font-medium">
                                            Hapus
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs italic">Akun Anda sedang aktif</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>