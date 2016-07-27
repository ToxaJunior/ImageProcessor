<?php
/**
 * Created by PhpStorm.
 * User: anget
 * Date: 19.07.16
 * Time: 14:04
 */

namespace Boboyan\ContentBundle\ImageProcessor;


abstract class ImageProcessor
{

    protected $image;
    const DIR_SMALL = '/upload/images_small/';
    const DIR_LARGE = '/upload/images_large/';
    const DIR_ORIGINAL = '/upload/images_original/';

    // получаем файл на обработку
    public function __construct($image){

        $this->image = $image;
    }

    public function store(){}

    public function delete(){}

    public function show(){}

    public function resizeImg($name, $filePath, $param)
    {
        $im = new \Imagick($name);
        if(array_key_exists('thumbwidth', $param) and array_key_exists('thumbheight', $param)){
            $thumbwidth = (int)$param['thumbwidth'];
            $thumbheight = (int)$param['thumbheight'];
            $im->thumbnailImage($thumbwidth, $thumbheight);
        }
        if(array_key_exists('cropwidth', $param) and array_key_exists('cropheight', $param)){
            $cropwidth = (int)$param['cropwidth'];
            $cropheight = (int)$param['cropheight'];
            $im->cropThumbnailImage($cropwidth, $cropheight);
        }
        $im->writeImage($filePath);
    }
}