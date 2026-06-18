<?php
function tampilkanHasil($nama, $idDev, $frameworks, $pengalaman, $tools, $minat, $skill) {
    echo '<div class="mt-6 bg-white border rounded-lg p-5">';
    echo '<h2 class="font-bold text-lg mb-3 text-gray-700">Hasil Input</h2>';
    echo '<table class="w-full text-sm border border-gray-200">';
    echo '<tr class="border-b"><td class="p-2 bg-gray-50 font-medium w-32">Nama</td><td class="p-2">' . $nama . '</td></tr>';
    echo '<tr class="border-b"><td class="p-2 bg-gray-50 font-medium">ID Dev</td><td class="p-2 font-mono text-blue-600">' . $idDev . '</td></tr>';
    echo '<tr class="border-b"><td class="p-2 bg-gray-50 font-medium">Framework</td><td class="p-2">' . implode(', ', $frameworks) . '</td></tr>';
    echo '<tr class="border-b"><td class="p-2 bg-gray-50 font-medium">Tools</td><td class="p-2">' . implode(', ', $tools) . '</td></tr>';
    echo '<tr class="border-b"><td class="p-2 bg-gray-50 font-medium">Minat</td><td class="p-2">' . $minat . '</td></tr>';
    echo '<tr><td class="p-2 bg-gray-50 font-medium">Skill</td><td class="p-2">' . $skill . '</td></tr>';
    echo '</table>';
    echo '<p class="mt-3 text-sm text-gray-600"><span class="font-medium">Pengalaman:</span> ' . $pengalaman . '</p>';
    if (count($frameworks) > 2) {
        echo '<p class="mt-3 text-green-600 font-semibold text-sm">🚀 Skill Anda cukup luas di bidang development!</p>';
    }
    echo '</div>';
}

$errors = [];
$nama = $idDev = $fwInput = $pengalaman = $minat = $skill = '';
$tools = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama       = $_POST['nama'];
    $idDev      = $_POST['id_dev'];
    $fwInput    = $_POST['frameworks'];
    $pengalaman = $_POST['pengalaman'];
    $tools      = $_POST['tools'] ?? [];
    $minat      = $_POST['minat'] ?? '';
    $skill      = $_POST['skill'];

    if (empty($nama))       $errors[] = 'Nama wajib diisi.';
    if (empty($idDev))      $errors[] = 'ID Developer wajib diisi.';
    if (empty($fwInput))    $errors[] = 'Framework wajib diisi.';
    if (empty($pengalaman)) $errors[] = 'Pengalaman wajib diisi.';
    if (empty($tools))      $errors[] = 'Pilih minimal satu tool.';
    if (empty($minat))      $errors[] = 'Minat bidang wajib dipilih.';
    if (empty($skill))      $errors[] = 'Tingkat skill wajib dipilih.';

    if (empty($errors)) {
        $frameworks = explode(',', $fwInput);
        tampilkanHasil($nama, $idDev, $frameworks, $pengalaman, $tools, $minat, $skill);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Developer</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8">
<div class="max-w-2xl mx-auto px-4">

    <h1 class="text-2xl font-bold text-gray-800 mb-1">Profil Developer</h1>
    <p class="text-sm text-gray-500 mb-5">Halaman profil dan form isian developer</p>

    <div class="bg-white border rounded-lg p-5 mb-5">
        <h2 class="font-bold text-gray-700 mb-3">Data Diri</h2>
        <table class="w-full text-sm border border-gray-200">
            <tr class="border-b"><td class="p-2 bg-gray-50 w-36">Nama</td><td class="p-2">Muhammad Maulidan Habibi</td></tr>
            <tr class="border-b"><td class="p-2 bg-gray-50">ID Developer</td><td class="p-2 font-mono">DEV767676</td></tr>
            <tr class="border-b"><td class="p-2 bg-gray-50">Kota/Tgl Lahir</td><td class="p-2">Gresik, 16 Maret 2007</td></tr>
            <tr class="border-b"><td class="p-2 bg-gray-50">Email</td><td class="p-2">habibigaming@email.com</td></tr>
            <tr><td class="p-2 bg-gray-50">WhatsApp</td><td class="p-2">085189881290</td></tr>
        </table>
    </div>

    <?php if (!empty($errors)): ?>
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4 text-sm text-red-600">
        <?php foreach ($errors as $e): ?><p>⚠ <?= $e ?></p><?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="bg-white border rounded-lg p-5 mt-5">
        <h2 class="font-bold text-gray-700 mb-4">Form Isian</h2>
        <form method="POST" class="space-y-4 text-sm">
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-gray-600 mb-1">Nama *</label>
                    <input type="text" name="nama" value="<?= $nama ?>" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-400"></div>
                <div><label class="block text-gray-600 mb-1">ID Developer *</label>
                    <input type="text" name="id_dev" value="<?= $idDev ?>" class="w-full border rounded px-3 py-2 font-mono focus:outline-none focus:ring-1 focus:ring-blue-400"></div>
            </div>
            <div><label class="block text-gray-600 mb-1">Framework/Tools (pisah koma) *</label>
                <input type="text" name="frameworks" value="<?= $fwInput ?>" placeholder="Laravel, React, Vue..." class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-400"></div>
            <div><label class="block text-gray-600 mb-1">Pengalaman Singkat *</label>
                <textarea name="pengalaman" rows="3" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-400"><?= $pengalaman ?></textarea></div>
            <div><label class="block text-gray-600 mb-2">Tools Penunjang *</label>
                <div class="flex flex-wrap gap-2">
                    <?php foreach (['VS Code','GitHub','Figma','Postman','Docker','Notion'] as $t): ?>
                    <label class="flex items-center gap-1.5 border rounded px-3 py-1.5 cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="tools[]" value="<?= $t ?>" <?= in_array($t, $tools) ? 'checked' : '' ?>> <?= $t ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-gray-600 mb-2">Minat Bidang *</label>
                    <div class="flex gap-3">
                        <?php foreach (['Frontend','Backend','Fullstack'] as $m): ?>
                        <label class="flex items-center gap-1.5"><input type="radio" name="minat" value="<?= $m ?>" <?= $minat==$m ? 'checked' : '' ?>> <?= $m ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div><label class="block text-gray-600 mb-1">Tingkat Skill *</label>
                    <select name="skill" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-400">
                        <option value="">-- Pilih --</option>
                        <?php foreach (['Dasar','Cukup','Profesional'] as $s): ?>
                        <option value="<?= $s ?>" <?= $skill==$s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2.5 rounded transition">Submit Profil</button>
        </form>
    </div>

    <div class="mt-5 text-sm text-center space-x-4">
        <a href="timeline.php" class="text-blue-500 hover:underline">Timeline Belajar</a>
        <a href="blog.php" class="text-blue-500 hover:underline">Blog Developer</a>
    </div>
</div>
</body>
</html