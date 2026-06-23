<?php

$dirs = [
    'vendor/almasaeed2010/adminlte/docs',
    'vendor/almasaeed2010/adminlte/pages',
    'vendor/almasaeed2010/adminlte/plugins',
    'vendor/almasaeed2010/adminlte/dist',
    'vendor/almasaeed2010/adminlte/.github',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        echo "Skipping (not found): $dir\n";
        continue;
    }
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
        if ($file->isDir()) {
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($dir);
    echo "Removed: $dir\n";
}

// Remove DejaVu fonts from dompdf
$fontDirs = glob('vendor/dompdf/dompdf/lib/fonts/DejaVu*');
foreach ($fontDirs as $f) {
    unlink($f);
    echo "Removed font: $f\n";
}
