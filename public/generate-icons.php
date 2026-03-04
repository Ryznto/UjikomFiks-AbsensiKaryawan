<?php
// Jalanin sekali: php public/generate-icons.php
// Lalu hapus file ini

function makeIcon(int $size, string $path): void {
    $img = imagecreatetruecolor($size, $size);
    $bg  = imagecolorallocate($img, 79, 124, 255);
    $fg  = imagecolorallocate($img, 255, 255, 255);

    imagefill($img, 0, 0, $bg);

    // Rounded feel — gambar lingkaran di tengah
    $margin = (int)($size * 0.15);
    imagefilledellipse($img, $size/2, $size/2, $size - $margin, $size - $margin, $fg);

    // Huruf A di tengah
    $fontSize = (int)($size * 0.35);
    $font     = 5; // built-in font
    $text     = 'A';
    $tw       = imagefontwidth($font) * strlen($text);
    $th       = imagefontheight($font);
    imagestring($img, $font, ($size - $tw) / 2, ($size - $th) / 2, $text, $bg);

    imagepng($img, $path);
    imagedestroy($img);
}

makeIcon(192, __DIR__ . '/icons/icon-192.png');
makeIcon(512, __DIR__ . '/icons/icon-512.png');

echo "Icons generated!";