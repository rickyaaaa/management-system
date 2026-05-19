<?php
/**
 * Storage Link Helper untuk Hostinger (exec/symlink dinonaktifkan)
 * Akses sekali via browser: https://healthifyworkout.my.id/public/link-storage.php
 * HAPUS file ini setelah berhasil dijalankan!
 */

$token = 'bigant2026'; // Ganti dengan password rahasia Anda
if (!isset($_GET['token']) || $_GET['token'] !== $token) {
    http_response_code(403);
    die('<h3 style="color:red">403 Forbidden — Tambahkan ?token=bigant2026 di URL</h3>');
}

$publicStoragePath = __DIR__ . '/storage';          // public/storage
$storageAppPublic  = dirname(__DIR__) . '/storage/app/public';

echo '<pre style="font-family:monospace;padding:16px;">';
echo "=== Storage Link Script ===\n\n";
echo "Target symlink : {$publicStoragePath}\n";
echo "Menuju ke      : {$storageAppPublic}\n\n";

// Pastikan storage/app/public ada
if (!is_dir($storageAppPublic)) {
    mkdir($storageAppPublic, 0775, true);
    echo "✅ Direktori storage/app/public dibuat\n";
}

// Jika sudah ada symlink / folder, skip atau hapus dulu
if (is_link($publicStoragePath)) {
    echo "⚠️  Symlink sudah ada, tidak perlu dibuat ulang.\n";
} elseif (is_dir($publicStoragePath)) {
    echo "⚠️  Folder public/storage sudah ada sebagai direktori biasa.\n";
} else {
    // Coba buat symlink
    if (function_exists('symlink') && @symlink($storageAppPublic, $publicStoragePath)) {
        echo "✅ Symlink berhasil dibuat!\n";
        echo "   {$publicStoragePath} -> {$storageAppPublic}\n";
    } else {
        // Fallback: buat direktori dan tambahkan .htaccess redirect
        echo "⚠️  symlink() gagal (dinonaktifkan hosting). Menggunakan fallback...\n";
        mkdir($publicStoragePath, 0775, true);

        $htaccess = "Options -Indexes\n"
            . "RewriteEngine On\n"
            . "RewriteRule ^(.*)$ ../../../../storage/app/public/\$1 [L]\n";
        file_put_contents($publicStoragePath . '/.htaccess', $htaccess);

        $indexPhp = "<?php\n"
            . "\$file = '../../../../storage/app/public/' . ltrim(str_replace('/storage/', '', \$_SERVER['REQUEST_URI']), '/');\n"
            . "if (file_exists(\$file)) {\n"
            . "    \$mime = mime_content_type(\$file) ?: 'application/octet-stream';\n"
            . "    header('Content-Type: ' . \$mime);\n"
            . "    readfile(\$file);\n"
            . "} else { http_response_code(404); echo '404 Not Found'; }\n";
        file_put_contents($publicStoragePath . '/index.php', $indexPhp);

        echo "✅ Fallback direktori public/storage dibuat dengan proxy PHP.\n";
    }
}

echo "\n✅ Selesai! HAPUS file ini sekarang via File Manager Hostinger.\n";
echo '</pre>';
