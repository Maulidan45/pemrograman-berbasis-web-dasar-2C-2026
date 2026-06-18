<?php
require 'auth.php';
require 'config.php';

$uid  = $_SESSION['user_id'];
$role = $_SESSION['role'];
$id   = (int)($_GET['id'] ?? 0);

if ($role == 'admin') {
    $q = $conn->prepare("SELECT * FROM todos WHERE id = ?");
    $q->bind_param("i", $id);
} else {
    $q = $conn->prepare("SELECT * FROM todos WHERE id = ? AND user_id = ?");
    $q->bind_param("ii", $id, $uid);
}

$q->execute();
$todo = $q->get_result()->fetch_assoc();

if (!$todo) {
    header('Location: index.php');
    exit;
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title  = $_POST['title'];
    $desc   = $_POST['description'];
    $prio   = $_POST['priority'];
    $status = $_POST['status'];
    $due    = $_POST['due_date'] ?: null;
    $time   = $_POST['due_time'] ?: null;

    $is_bentrok = false;

    /* VALIDASI JADWAL BENTROK */
    if ($due && $time) {
        $cek = $conn->prepare("SELECT id FROM todos WHERE user_id = ? AND due_date = ? AND due_time = ? AND id != ?");
        $cek->bind_param("issi", $uid, $due, $time, $id);
        $cek->execute();
        $bentrok = $cek->get_result();

        if ($bentrok->num_rows > 0) {
            $is_bentrok = true;
            $msg = 'Jam kegiatan bentrok!';
        }
    }

    if (!$is_bentrok) {
        $upd = $conn->prepare("UPDATE todos SET title = ?, description = ?, priority = ?, status = ?, due_date = ?, due_time = ? WHERE id = ?");
        $upd->bind_param("ssssssi", $title, $desc, $prio, $status, $due, $time, $id);
        $upd->execute();

        $msg = 'Todo diperbarui!';

        $todo = array_merge($todo, [
            'title'       => $title,
            'description' => $desc,
            'priority'    => $prio,
            'status'      => $status,
            'due_date'    => $due,
            'time'        => $time
        ]);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Todo</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-blue-600 text-white p-4">
        <a href="index.php" class="hover:underline">← Kembali</a>
        <span class="ml-4 font-bold">Edit Todo</span>
    </nav>

    <div class="max-w-lg mx-auto mt-6 px-4">

        <?php if ($msg): ?>
            <p class="bg-green-100 text-green-700 p-2 rounded mb-4"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>
        
        <div class="bg-white p-4 rounded shadow">
            <form method="POST" onsubmit="return !!document.querySelector('[name=title]').value">
                
                <label class="block text-sm mb-1 font-medium text-gray-700">Judul*</label>
                <input name="title" value="<?= htmlspecialchars($todo['title']) ?>" required class="w-full border p-2 rounded mb-3" maxlength="100">

                <label class="block text-sm mb-1 font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" class="w-full border p-2 rounded mb-3" rows="3"><?= htmlspecialchars($todo['description']) ?></textarea>

                <div class="flex gap-2 mb-3">
                    <div class="flex-1">
                        <label class="block text-sm mb-1 font-medium text-gray-700">Prioritas</label>
                        <select name="priority" class="w-full border p-2 rounded">
                            <?php foreach(['low', 'medium', 'high'] as $p): ?>
                                <option value="<?= $p ?>" <?= $todo['priority'] == $p ? 'selected' : '' ?>><?= ucfirst($p) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm mb-1 font-medium text-gray-700">Status</label>
                        <select name="status" class="w-full border p-2 rounded">
                            <option value="pending" <?= $todo['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="done" <?= $todo['status'] == 'done' ? 'selected' : '' ?>>Done</option>
                        </select>
                    </div>
                </div>

                <label class="block text-sm mb-1 font-medium text-gray-700">Due Date</label>
                <div class="flex gap-2 mb-4">
                    <input name="due_date" type="date" value="<?= htmlspecialchars($todo['due_date'] ?? '') ?>" class="w-full border p-2 rounded">
                    <input name="due_time" type="time" value="<?= htmlspecialchars(substr($todo['due_time'] ?? '', 0, 5)) ?>" class="w-full border p-2 rounded">
                </div>

                <div class="flex items-center gap-2">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer">Simpan</button>
                    <a href="index.php" class="text-gray-500 hover:underline text-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>