<?php
/**
 * Created by PhpStorm.
 * User: anget
 * Date: 19.07.16
 * Time: 14:09
 */

namespace Boboyan\ContentBundle\ImageProcessor;


class LocalImageProcessor implements ImageProcessor
{
    public $new_path;
    /**
     * LocalImageProcessor constructor.
     */
    public function __construct($path)
    {
        $this->new_path = $path;
    }

    public function delete($fileId)
    {

    }

    private function createDir($param){

        // создаем директории для сохранения
        if(!file_exists($param)){
            mkdir($param);
        }
        chmod($param, 0777);
    }

    public function save($param)
    {
        // генерируем новое имя файла
        $fileName = md5(uniqid()).'.'.$param['ext'];
        // чекаем и если нужно создаем директорию
        $this->createDir($this->new_path);
        //сохраняем оригинал изображения
        rename($param['temp_path'], $this->new_path.$fileName);

        return $this->new_path.$fileName;
    }

    public function show($fileId)
    {
     // сформировать и вернуть урл
    }

}