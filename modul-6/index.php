<?php
require 'auth.php';
require 'config.php';

$uid  = $_SESSION['user_id'];
$role = $_SESSION['role'];
$msg  = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) { 
    $title = $_POST['title']; 
    $desc  = $_POST['description']; 
    $prio  = $_POST['priority']; 
    $due   = $_POST['due_date'] ?: null; 
    $time  = $_POST['due_time'] ?: null; 
    
    $is_bentrok = false;

    if ($due && $time) { 
        $cek = $conn->prepare("SELECT id FROM todos WHERE user_id = ? AND due_date = ? AND due_time = ?"); 
        $cek->bind_param("iss", $uid, $due, $time); 
        $cek->execute(); 
        $bentrok = $cek->get_result(); 
        
        if ($bentrok->num_rows > 0) { 
            $is_bentrok = true;
            $msg = 'Jam kegiatan bentrok!'; 
        } 
    } 

    if (!$is_bentrok) {
        $stmt = $conn->prepare("INSERT INTO todos (user_id, title, description, priority, due_date, due_time) VALUES (?, ?, ?, ?, ?, ?)"); 
        $stmt->bind_param("isssss", $uid, $title, $desc, $prio, $due, $time); 
        $stmt->execute(); 
        $msg = 'Todo ditambahkan!'; 
    }
}

if (isset($_GET['done'])) {
    $id = (int)$_GET['done'];
    $q  = ($role == 'admin')
        ? $conn->prepare("UPDATE todos SET status='done' WHERE id=?")
        : $conn->prepare("UPDATE todos SET status='done' WHERE id=? AND user_id=?");
    
    if ($role == 'admin') {
        $q->bind_param("i", $id);
    } else {
        $q->bind_param("ii", $id, $uid);
    }
    
    $q->execute();
    header('Location: index.php'); 
    exit;
}

if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    if ($role == 'admin') {
        $q = $conn->prepare("DELETE FROM todos WHERE id=?");
        $q->bind_param("i", $id);
    } else {
        $q = $conn->prepare("DELETE FROM todos WHERE id=? AND user_id=?");
        $q->bind_param("ii", $id, $uid);
    }
    $q->execute();
    header('Location: index.php'); 
    exit;
}

if ($role == 'admin') {
    $todos = $conn->query("SELECT t.*, u.username FROM todos t JOIN users u ON t.user_id=u.id ORDER BY t.created_at DESC");
} else {
    $stmt = $conn->prepare("SELECT * FROM todos WHERE user_id=? ORDER BY created_at DESC");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $todos = $stmt->get_result();
}

$prio_color = ['low' => 'green', 'medium' => 'yellow', 'high' => 'red'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-blue-600 text-white p-4 flex justify-between items-center">
        <span class="font-bold">Todo App 
            <span class="text-xs bg-blue-800 px-2 py-1 rounded ml-2"><?= htmlspecialchars($_SESSION['role']) ?></span>
        </span>
        <span>
            <?php if ($role == 'admin'): ?>
                <a href="users.php" class="text-sm mr-4 underline">Kelola User</a>
            <?php endif; ?>
            Hai, <?= htmlspecialchars($_SESSION['username']) ?> &nbsp;
            <a href="logout.php" class="bg-white text-blue-600 px-3 py-1 rounded text-sm">Logout</a>
        </span>
    </nav>

    <div class="max-w-3xl mx-auto mt-6 px-4">

        <?php if ($msg): ?>
            <p class="bg-green-100 text-green-700 p-2 rounded mb-4"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>

        <div class="bg-white p-4 rounded shadow mb-6">
            <h2 class="font-bold mb-3">Tambah Todo</h2>
            <form method="POST" onsubmit="return !!document.querySelector('[name=title]').value">
                <input name="title" placeholder="Judul todo*" required class="w-full border p-2 rounded mb-2" maxlength="100">
                <textarea name="description" placeholder="Deskripsi (opsional)" class="w-full border p-2 rounded mb-2" rows="2"></textarea>
                
                <div class="flex gap-2 mb-2">
                    <select name="priority" class="border p-2 rounded flex-1">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                    <input name="due_date" type="date" class="border p-2 rounded flex-1">
                    <input name="due_time" type="time" class="border p-2 rounded flex-1">
                </div>
                <button name="add" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah</button>
            </form>
        </div>

        <div class="bg-white rounded shadow">
            <h2 class="font-bold p-4 border-b"><?= $role == 'admin' ? 'Semua Todo' : 'Todo Saya' ?></h2>
            
            <?php if ($todos->num_rows == 0): ?>
                <p class="p-4 text-gray-500">Belum ada todo.</p>
            <?php endif; ?>

            <?php while ($t = $todos->fetch_assoc()): ?>
                <div class="p-4 border-b flex justify-between items-start <?= $t['status'] == 'done' ? 'bg-gray-50' : '' ?>">
                    <div>
                        <p class="font-medium <?= $t['status'] == 'done' ? 'line-through text-gray-400' : '' ?>">
                            <?= htmlspecialchars($t['title']) ?>
                        </p>
                        
                        <?php if ($t['description']): ?>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($t['description']) ?></p>
                        <?php endif; ?>
                        
                        <div class="flex gap-2 mt-1 text-xs">
                        
                            <span class="bg-<?= $prio_color[$t['priority']] ?>-100 text-<?= $prio_color[$t['priority']] ?>-700 px-2 py-0.5 rounded">
                                <?= ucfirst($t['priority']) ?>
                            </span>

                        
                            <?php if ($t['due_date']): ?>
                                <span class="text-gray-400">
                                    Deadline: <?= htmlspecialchars($t['due_date']) ?>
                                    <?php if (!empty($t['due_time'])): ?>
                                        <?= htmlspecialchars(substr($t['due_time'], 0, 5)) ?>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>

                       
                            <?php if ($role == 'admin' && isset($t['username'])): ?>
                                <span class="text-gray-400">oleh: <?= htmlspecialchars($t['username']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex gap-2 ml-4">
                        <?php if ($t['status'] == 'pending'): ?>
                            <a href="?done=<?= $t['id'] ?>" class="text-green-600 text-sm hover:underline">✓ Done</a>
                        <?php endif; ?>
                        <a href="edit.php?id=<?= $t['id'] ?>" class="text-blue-600 text-sm hover:underline">Edit</a>
                        <a href="?del=<?= $t['id'] ?>" onclick="return confirm('Hapus todo ini?')" class="text-red-500 text-sm hover:underline">Hapus</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>