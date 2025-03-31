<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\CategoryMetaMutations\TypeAPIs\CategoryMetaTypeMutationAPIInterface;

class CategoryMetaTypeMutationAPIFacade
{
    public static function getInstance(): CategoryMetaTypeMutationAPIInterface
    {
        /**
         * @var CategoryMetaTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(CategoryMetaTypeMutationAPIInterface::class);
        return $service;
    }
}
