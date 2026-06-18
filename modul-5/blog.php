<?php
$artikel = [
    'html'      => ['judul'=>'Belajar HTML Pertama Kali',       'tanggal'=>'20 Maret 2026',    'isi'=>'Hari pertama nulis tag HTML rasanya ajaib. Nulis kode, refresh browser, muncul tulisan. Dari situ aku jatuh cinta sama web.', 'gambar'=>'gue.png'],
    'error'     => ['judul'=>'Error Pertama yang Bikin Panik',  'tanggal'=>'29 Maret 2026',    'isi'=>'Lupa tutup kurung kurawal, satu jam nyarinya. Tapi dari situ aku belajar baca pesan error dengan teliti.', 'gambar'=>'gue.png'],
    'proyek'    => ['judul'=>'Website Pertama Berhasil Tayang', 'tanggal'=>'1 April 2026',   'isi'=>'Jam 11 malam deploy project. Tampilannya jelek tapi rasanya luar biasa', 'gambar'=>'gue.png'],
    'laravel'   => ['judul'=>'Jatuh Cinta dengan PHP',      'tanggal'=>'3 Mei 2026',   'isi'=>'Sebelumnya belajar PHP. Pakai PHP 1 jam Langsung ketagihan', 'gambar'=>'gue.png'],
    'freelance' => ['judul'=>'Bisa PHP dikit',        'tanggal'=>'5 Mei 2026', 'isi'=>'bisa sedikit memahami tentang PHP.', 'gambar'=>'gue.png'],
];

$kutipan = ['Mulai dulu, sempurnakan kemudian', 'Error adalah guru terbaik', 'Konsistensi mengalahkan bakat', 'Satu commit sehari, satu langkah maju.'];
$kutipanAcak = $kutipan[array_rand($kutipan)];

$pilihan = $_GET['artikel'] ?? '';
$dipilih = $artikel[$pilihan] ?? null;

$keys = array_keys($artikel);
$idx  = array_search($pilihan, $keys);
$prev = ($idx > 0) ? $keys[$idx - 1] : null;
$next = ($idx !== false && $idx < count($keys) - 1) ? $keys[$idx + 1] : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Blog Developer</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-3xl mx-auto">

    <h1 class="text-xl font-bold mb-2">Blog Reflektif Developer</h1>

    <p class="text-sm italic text-gray-500 border-l-4 border-blue-300 pl-3 mb-5">"<?= $kutipanAcak ?>"</p>

    <div class="grid grid-cols-[180px_1fr] gap-4">

        <div class="bg-white border rounded p-3 text-sm space-y-1">
            <p class="font-semibold mb-2">Artikel</p>
            <?php foreach ($artikel as $key => $art): ?>
            <a href="?artikel=<?= $key ?>"
               class="block px-2 py-1 rounded hover:bg-blue-50 hover:text-blue-600
                      <?= $pilihan === $key ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600' ?>">
                <?= $art['judul'] ?>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="bg-white border rounded p-4 text-sm">
            <?php if ($dipilih): ?>
                <img src="<?= $dipilih['gambar'] ?>" class="w-full h-36 object-cover rounded mb-3 bg-gray-100"
                     onerror="this.style.display='none'">
                <p class="text-xs text-gray-400 mb-1"><?= $dipilih['tanggal'] ?></p>
                <h2 class="font-bold text-base mb-2"><?= $dipilih['judul'] ?></h2>
                <p class="text-gray-600 leading-relaxed"><?= $dipilih['isi'] ?></p>
                <div class="mt-4 flex justify-between text-xs">
                    <?php if ($prev): ?>
                        <a href="?artikel=<?= $prev ?>" class="text-blue-500 hover:underline">← Sebelumnya</a>
                    <?php else: ?>
                        <span class="text-gray-300">← Sebelumnya</span>
                    <?php endif; ?>
                    <?php if ($next): ?>
                        <a href="?artikel=<?= $next ?>" class="text-blue-500 hover:underline">Berikutnya →</a>
                    <?php else: ?>
                        <span class="text-gray-300">Berikutnya →</span>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-400">Pilih artikel di sebelah kiri.</p>
            <?php endif; ?>
        </div>

    </div>

    <div class="mt-4 text-sm space-x-3">
        <a href="index.php" class="text-blue-500 hover:underline">← Kembali ke Profil</a>
        <a href="timeline.php" class="text-blue-500 hover:underline">Timeline Belajar</a>
    </div>
</div>
</body>
</html>