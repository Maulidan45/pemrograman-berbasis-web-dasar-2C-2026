<?php
function formatTahun($tahun) {
    $highlight = ['2025'];
    return in_array($tahun, $highlight) ? "<b class='text-blue-600'>$tahun</b>" : $tahun;
}

$timeline = [
    ['tahun'=>'2025', 'judul'=>'Masuk Kuliah Sistem Informasi',    'cerita'=>'Mulai belajar pemrograman secara formal.'],
    ['tahun'=>'2026', 'judul'=>'Belajar HTML & CSS',          'cerita'=>'Membuat halaman web statis pertama kali.'],
    ['tahun'=>'2026', 'judul'=>'Belajar PHP & JavaScript',    'cerita'=>'Mulai paham logika dan membuat form dinamis.'],
    ['tahun'=>'2026', 'judul'=>'Membuat Website Pertama',      'cerita'=>'Deploy website ke hosting, bangga sekali!'],
    ['tahun'=>'2026', 'judul'=>'Belajar implementasi',   'cerita'=>'deploy pertama dengan php hanya 30 menit.'],
    ['tahun'=>'2026', 'judul'=>'Ikut Proyek Tim P2MW',      'cerita'=>'Belajar kolaborasi.'],
    ['tahun'=>'2026', 'judul'=>'Freelance Pertama',           'cerita'=>'Membuat landing page'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Timeline Belajar</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-xl mx-auto">

    <h1 class="text-xl font-bold mb-4">Timeline Perjalanan Belajar Coding</h1>

    <div class="relative border-l-2 border-gray-300 pl-5 space-y-4">
        <?php foreach ($timeline as $item): ?>
        <div class="bg-white border rounded p-3 text-sm">
            <p class="text-gray-500 text-xs mb-1"><?= formatTahun($item['tahun']) ?></p>
            <p class="font-semibold"><?= $item['judul'] ?></p>
            <p class="text-gray-600 mt-1"><?= $item['cerita'] ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-5 text-sm space-x-3">
        <a href="index.php" class="text-blue-500 hover:underline">← Kembali ke Profil</a>
        <a href="blog.php" class="text-blue-500 hover:underline">Blog Developer →</a>
    </div>
</div>
</body>
</html>