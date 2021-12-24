<?php
function resizeImage($image, $w, $h)
{
    $oldw = imagesx($image);
    $oldh = imagesy($image);
    $temp = imagecreatetruecolor($w, $h);
    imagecopyresampled($temp, $image, 0, 0, 0, 0, $w, $h, $oldw, $oldh);
    return $temp;
}

function resizeAndWriteImage($imagePath, $outputPath, $w, $h) {
    $uploadedImage = imagecreatefromjpeg($imagePath);
    if (!$uploadedImage) {
        throw new Exception("The uploaded file is corrupted (or wrong format)");
    } else {
        $resizedImage = resizeImage($uploadedImage, 200, 125);    
        if (!imagejpeg($resizedImage, $outputPath)) {
                throw new Exception("Failed to save resized image");
        }
    }
}
?>