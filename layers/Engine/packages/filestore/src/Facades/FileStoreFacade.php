<?php

declare(strict_types=1);

namespace PoP\FileStore\Facades;

use PoP\Root\App;
use PoP\FileStore\Store\FileStoreInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class FileStoreFacade
{
    public static function getInstance(): FileStoreInterface
    {
        /**
         * @var FileStoreInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get('file_store');
        return $service;
    }
}
