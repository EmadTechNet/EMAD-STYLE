<?php
session_start();
$im = imagecreatetruecolor(160, 50);
$bg = imagecolorallocate($im, 255, 255, 255);
$fg = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, 160, 50, $bg);
$letters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$len = strlen($letters);
$txt = '';
for($i=0;$i<5;$i++){
    $txt .= $letters[rand(0,$len-1)];
}
$_SESSION['captcha_text'] = $txt;
$font = __DIR__ . '/arial.ttf';
if(!file_exists($font)){
    // use built-in font if ttf not available
    imagestring($im, 5, 30, 15, $txt, $fg);
} else {
    imagettftext($im, 22, rand(-10,10), 15, 35, $fg, $font, $txt);
}
// add some noise
for($i=0;$i<50;$i++){
    imagesetpixel($im, rand()%160, rand()%50, $fg);
}
header("Content-Type: image/png");
imagepng($im);
imagedestroy($im);
?>