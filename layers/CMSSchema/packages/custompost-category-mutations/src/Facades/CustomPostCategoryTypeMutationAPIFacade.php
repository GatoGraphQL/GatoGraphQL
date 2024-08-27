<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;

class CustomPostCategoryTypeMutationAPIFacade
{
    public static function getInstance(): CustomPostCategoryTypeMutationAPIInterface
    {
        /**
         * @var CustomPostCategoryTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(CustomPostCategoryTypeMutationAPIInterface::class);
        return $service;
    }
}
