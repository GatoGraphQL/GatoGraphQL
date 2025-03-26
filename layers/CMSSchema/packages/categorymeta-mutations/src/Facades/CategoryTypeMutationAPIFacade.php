<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\CategoryMetaMutations\TypeAPIs\CategoryTypeMutationAPIInterface;

class CategoryTypeMutationAPIFacade
{
    public static function getInstance(): CategoryTypeMutationAPIInterface
    {
        /**
         * @var CategoryTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(CategoryTypeMutationAPIInterface::class);
        return $service;
    }
}
