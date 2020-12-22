<?php

declare(strict_types=1);

namespace PoP\FileStore\Facades;

use PoP\FileStore\Store\FileStoreInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class JSONFileStoreFacade
{
    public static function getInstance(): FileStoreInterface
    {
        /**
         * @var FileStoreInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get('json_file_store');
        return $service;
    }
}
