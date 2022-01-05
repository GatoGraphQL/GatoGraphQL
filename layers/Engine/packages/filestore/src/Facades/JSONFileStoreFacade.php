<?php

declare(strict_types=1);

namespace PoP\FileStore\Facades;

use PoP\Root\App;
use PoP\FileStore\Store\FileStoreInterface;

class JSONFileStoreFacade
{
    public static function getInstance(): FileStoreInterface
    {
        /**
         * @var FileStoreInterface
         */
        $service = App::getContainer()->get('json_file_store');
        return $service;
    }
}
