<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\Facades;

use PoP\Engine\App;
use PoPSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;

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
