<?php
/**
 * Created by PhpStorm.
 * User: anget
 * Date: 19.07.16
 * Time: 14:09
 */

namespace Boboyan\ContentBundle\ImageProcessor;


class LocalImageProcessor extends ImageProcessor
{
    public function delete()
    {

    }

    private function createDir($param){

        // создаем директории для сохранения
        if(!file_exists($param)){
            mkdir($param);
        }
        chmod($param, 0777);
    }

    public function store($tmpName, $ext)
    {
        $fileName = md5(uniqid()).'.'.$ext;
        //echo $fileName,$tmpName;die();


        $this->dirSmall = realpath('').self::DIR_SMALL;
        $this->dirLarge = realpath('').self::DIR_LARGE;
        $this->dirOriginal = realpath('').self::DIR_ORIGINAL;

        // создаем директории
        $this->createDir($this->dirSmall);
        $this->createDir($this->dirLarge);
        $this->createDir($this->dirOriginal);

        // Создаем и сохраняем превьюшки
        $this->resizeImg($tmpName, $this->dirSmall . $fileName, array('thumbwidth' => 420, 'thumbheight' => 280));
        $this->resizeImg($tmpName, $this->dirLarge . $fileName, array('thumbwidth' => 0, 'thumbheight' => 600,
            'cropwidth' => 860, 'cropheight' => 600));

        //сохраняем оригинал изображения
        rename($tmpName, $this->dirOriginal.$fileName);

        return $fileName;
    }

    public function show($id)
    {
        $image = $this->getDoctrine()
            ->getRepository(self::PATH_ENTITY)
            ->find($id);

        if (!$image) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
    }

}