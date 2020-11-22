<?php
namespace App\Http\Helpers;

class PdfConvertHelper {
    /**
     * Convert PDF to Concated Image
     * @param string $targetFile
     * @param int $width
     * @param int $quality
     * @param boolean $rotate
     * @return string
     */
    public static function getConcatedImageFromPDF($targetFile, $width, $space, $quality, $rotate)
    {
        if( !file_exists($targetFile) )
            return false;

        $imageFiles = self::getImagesFromPDFByPage($targetFile);

        //Get Default PDF Image Width
        if( $width == 0 )
        {
            if( count($imageFiles) > 0 )
            {
                $imageSize = getimagesize($imageFiles[0]);
                $width = $imageSize[0];
            }
        }

        $concatedImage = self::concateImages($imageFiles, $width, $space, $quality, $rotate);

        return $concatedImage;
    }

    /**
     * Get Image Files Path from pdf file
     * @param string $pdfFile
     * @return array
     */
    public static function getImagesFromPDFByPage($pdfFile)
    {
        $tmpImgPre = "tmpImage" . uniqid();
        $imageFileExt = "jpg";

        $pdf = new \Spatie\PdfToImage\Pdf($pdfFile);
        $pdf->setColorspace(\Imagick::COLORSPACE_RGB);
        $pdf->setCompressionQuality(0);
        $pageCount = $pdf->getNumberOfPages();
        $imageFiles = [];

        for($i = 1; $i <= $pageCount; $i++)
        {
            $imgPath = "{$tmpImgPre}{$i}.{$imageFileExt}";
            if (file_exists($imgPath)) unlink($imgPath);
            $pdf->setPage($i)
                ->saveImage($imgPath);
            $imageFiles[] = $imgPath;
        }
        return $imageFiles;
    }

    /**
     * Concate several images as one
     * @param array $imageFiles
     * @param int $width
     * @param int $space
     * @param int $quality
     * @param boolean $rotate
     * @return string
     */
    public static function concateImages( $imageFiles, $width, $space, $quality, $rotate)
    {
        ini_set('memory_limit','256M');
        $concatedResources = [];

        $totalHeight = 0;

        foreach( $imageFiles as $imageFile )
        {
            //Create resource from file
            $imgResource = imagecreatefromjpeg($imageFile);

            //Rotate If needed
            if( $rotate == true )
                $imgResource = imagerotate($imgResource, -90, 0);

            //Crop image
            $imgResource = imagecropauto($imgResource, IMG_CROP_WHITE);

            $img = imagescale($imgResource, $width);
            $height = imagesy($img);
            $concatedResources[] = [
                'imageResource' => $img,
                'height'        => $height
            ];
            $totalHeight += $height;
            unlink($imageFile);
        }

        $concatedImage = imagecreatetruecolor($width, $totalHeight + (count($imageFiles) - 1) * $space );

        $color = imagecolorallocate($concatedImage, 255, 255, 255); //fill transparent back
        imagefill($concatedImage, 0, 0, $color);
        imagesavealpha($concatedImage, true);

        $copiedImageHeight = 0;
        foreach( $concatedResources as $key => $image )
        {
            imagecopyresampled($concatedImage, $image['imageResource'], 0, $copiedImageHeight, 0, 0, $width, $image['height'], $width, $image['height']);
            $copiedImageHeight += $image['height'] + $space;
        }

        $resultFile = "data/" . uniqid() . ".jpg";
        if (file_exists($resultFile)) unlink($resultFile);

        if(imagejpeg($concatedImage, $resultFile, $quality))
            return $resultFile;
    }
}
