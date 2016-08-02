<?php
/**
 * Created by PhpStorm.
 * User: anget
 * Date: 29.07.16
 * Time: 16:23
 */

namespace Boboyan\ContentBundle\ImageProcessor;


use Symfony\Component\DependencyInjection\Container;

class FileProcessorFactory
{
    private $processor;
    private $container;

    // принимаем имя сервиса(обработчика ресурсов) из конфига
    public function __construct($processor, Container $container)
    {
        $this->processor = $processor;
        $this->container = $container;
    }

    public function save($param){

        return $this->container->get($this->processor)->save($param);
    }
    public function delete($param){

        $this->container->get($this->processor)->delete($param);
    }
    public function show($param){

        return $this->container->get($this->processor)->show($param);
    }
}