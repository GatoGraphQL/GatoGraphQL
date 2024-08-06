<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\CategoryMutations\TypeAPIs\CategoryTypeMutationAPIInterface;

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
