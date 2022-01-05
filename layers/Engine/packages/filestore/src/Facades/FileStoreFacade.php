<?php

declare(strict_types=1);

namespace PoP\FileStore\Facades;

use PoP\Root\App;
use PoP\FileStore\Store\FileStoreInterface;

class FileStoreFacade
{
    public static function getInstance(): FileStoreInterface
    {
        /**
         * @var FileStoreInterface
         */
        $service = App::getContainer()->get('file_store');
        return $service;
    }
}
