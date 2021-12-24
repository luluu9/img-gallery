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

function watermarkImage($image, $watermarkText) {
    $WATERMARK_WIDTH = 300;
    $WATERMARK_HEIGHT = 300;
    $WATERMARK_PROPORTION = 15; // proportion of font to image 
    $FONT = 'fonts/verdana.ttf';

    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);
    $WATERMARK_FONTSIZE = (($imageHeight>$imageWidth) ? $imageHeight : $imageWidth)/$WATERMARK_PROPORTION;
    $x = imagesx($image)/2 - strlen($watermarkText)/2*$WATERMARK_FONTSIZE;
    $y = imagesy($image)/2 - $WATERMARK_FONTSIZE/2;
    $black = imagecolorallocatealpha($image, 0, 0, 0, 100);

    imagettftext($image, $WATERMARK_FONTSIZE, 0, $x + 0, $y + 0, $black, $FONT, $watermarkText);    
    
    return $image;
}

function watermarkAndWriteImage($imagePath, $outputPath, $watermarkText) {
  $uploadedImage = imagecreatefromjpeg($imagePath);
    if (!$uploadedImage) {
        throw new Exception("The uploaded file is corrupted (or wrong format)");
    } else {
        $watermarkedImage = watermarkImage($uploadedImage, $watermarkText);    
        if (!imagejpeg($watermarkedImage, $outputPath)) {
                throw new Exception("Failed to save resized image");
        }
    }
}

?>