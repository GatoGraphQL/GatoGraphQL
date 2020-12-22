<?php

declare(strict_types=1);

namespace PoP\FileStore\Facades;

use PoP\FileStore\Renderer\FileRendererInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class FileRendererFacade
{
    public static function getInstance(): FileRendererInterface
    {
        /**
         * @var FileRendererInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(FileRendererInterface::class);
        return $service;
    }
}
