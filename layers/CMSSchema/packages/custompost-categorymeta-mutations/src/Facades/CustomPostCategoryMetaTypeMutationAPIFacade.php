<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeAPIs\CustomPostCategoryMetaTypeMutationAPIInterface;

class CustomPostCategoryMetaTypeMutationAPIFacade
{
    public static function getInstance(): CustomPostCategoryMetaTypeMutationAPIInterface
    {
        /**
         * @var CustomPostCategoryMetaTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(CustomPostCategoryMetaTypeMutationAPIInterface::class);
        return $service;
    }
}
