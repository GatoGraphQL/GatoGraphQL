<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;

class PostCategoryTypeMutationAPIFacade
{
    public static function getInstance(): PostCategoryTypeMutationAPIInterface
    {
        /**
         * @var PostCategoryTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(PostCategoryTypeMutationAPIInterface::class);
        return $service;
    }
}
