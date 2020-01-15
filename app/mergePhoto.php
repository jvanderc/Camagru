<?php

$photo = imagecreatefromstring(base64_decode($_POST['photo']));
$image = imagecreatefrompng('../public/img/image'. $_POST['image'] . '.png');

imagealphablending($image, false);
imagesavealpha($image, true);
imagecopy($photo, $image, 10, 10, 0, 0, 100, 100);
ob_start();
imagejpeg($photo, null, 100);
$content = ob_get_contents();
ob_end_clean();

echo json_encode(base64_encode($content));
imagedestroy($photo);
imagedestroy($image);

?>