<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/app', function () {
    return view('app');
});

Route::get('/icon{size}.png', function ($size) {
    $size = (int) $size;
    if (!in_array($size, [192, 512])) abort(404);

    $img   = imagecreatetruecolor($size, $size);
    $bg    = imagecolorallocate($img, 30, 58, 138);
    $white = imagecolorallocate($img, 255, 255, 255);
    imagefill($img, 0, 0, $bg);

    $text     = 'MM';
    $fontSize = (int)($size * 0.28);
    $font     = 5;
    $tw       = imagefontwidth($font) * strlen($text);
    $th       = imagefontheight($font);
    imagestring($img, $font, (int)(($size - $tw) / 2), (int)(($size - $th) / 2), $text, $white);

    ob_start();
    imagepng($img);
    $png = ob_get_clean();
    imagedestroy($img);

    return response($png, 200)->header('Content-Type', 'image/png');
});
