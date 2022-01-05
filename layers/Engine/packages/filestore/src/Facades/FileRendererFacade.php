<?php

declare(strict_types=1);

namespace PoP\FileStore\Facades;

use PoP\Root\App;
use PoP\FileStore\Renderer\FileRendererInterface;

class FileRendererFacade
{
    public static function getInstance(): FileRendererInterface
    {
        /**
         * @var FileRendererInterface
         */
        $service = App::getContainer()->get(FileRendererInterface::class);
        return $service;
    }
}
