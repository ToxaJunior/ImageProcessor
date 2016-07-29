<?php

namespace Boboyan\ContentBundle\ImageProcessor;


interface ImageProcessor
{
    /**
     * @param $param
     * @return mixed
     */
    public function save($param);

    /**
     * @param $fileId
     * @return mixed
     */
    public function delete($fileId);

    /**
     * @param $fileId
     * @return mixed
     */
    public function show($fileId);

}