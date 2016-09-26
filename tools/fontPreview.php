<?php

$defaultPreviewText = "AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz";
$previewHeight = 60;
$previewFontSize = 30;
$previewFontColour = Array(0,0,0); // R,G,B -- 0-255
$previewBackgroundColour = Array(255,255,255); // R,G,B -- 0-255


// -------------------------------------------------------


require_once("../settings.php");
require_once("../functions.php");
require_once("../process.php");


$previewText = $defaultPreviewText;
$tempFont = '../' . $cache_location . uniqid() . 'tempfont-'.$_GET['id'].'.ttf';
$indent = ( $previewHeight - $previewFontSize ) / 2;

$error = 0;

if (isset($_GET['previewText'])) {
    $previewText = $_GET['previewText'];
}


if (isset($_GET['id']) && $_GET['id'] !== 'new') {
    $font = getFont($_GET['id']);
    
    require_once('../storage/fonts/' . $font['font_file'] . '.php'); // Reads a bunch of values from the font definition file, bit messy -- could clobber
    $fontFile = '../storage/fonts/' . ($file);
} else {
    $error++;
}


if ($error > 0) {
    $previewFontColour = Array(200,20,20);
    $previewText = ($_GET['id'] === 'new'?"(New font)":"ERROR");
    $fontFile = '../install/fonts/roboto_regular.z'; // Always gonna be there

}


file_put_contents($tempFont,gzuncompress(file_get_contents($fontFile)));

$bbox = imagettfbbox($previewFontSize, 0, $tempFont, $previewText);
$previewWidth = (2 * $indent) + abs(min($bbox[0],$bbox[6]) - max($bbox[2],$bbox[4]));

$img = imagecreatetruecolor($previewWidth,$previewHeight);
$fg = imagecolorallocate($img, $previewFontColour[0], $previewFontColour[1], $previewFontColour[2]);
$bg = imagecolorallocate($img, $previewBackgroundColour[0], $previewBackgroundColour[1], $previewBackgroundColour[2]);

imagefilledrectangle($img, 0, 0, $previewWidth-1, $previewHeight-1, $bg);

imagefttext ($img, $previewFontSize, 0, $indent, $previewHeight-$indent, $fg, $tempFont, $previewText);

header ('Content-Type: image/png');
imagepng($img);
imagedestroy($img);

if (file_exists($tempFont)) {
    unlink($tempFont);
}

?>
