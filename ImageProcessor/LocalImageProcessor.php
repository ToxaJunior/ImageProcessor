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

    public function store()
    {
        $this->dirSmall = realpath('').self::DIR_SMALL;
        $this->dirLarge = realpath('').self::DIR_LARGE;
        $this->dirOriginal = realpath('').self::DIR_ORIGINAL;

        //Создаем и сохраняем превьюшки
        $this->resizeImg($this->image, $this->dirSmall . $this->image, array('thumbwidth' => 420, 'thumbheight' => 280));
        $this->resizeImg($this->image, $this->dirLarge . $this->image, array('thumbwidth' => 0, 'thumbheight' => 600,
            'cropwidth' => 860, 'cropheight' => 600));

        //сохраняем оригинал изображения
        rename($this->image, $this->dirOriginal);
    }

    public function show($id)
    {
        $image = $this->getDoctrine()
            ->getRepository('Boboyan\ContentBundle\Entity\Image')
            ->find($id);

        if (!$image) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
    }

}