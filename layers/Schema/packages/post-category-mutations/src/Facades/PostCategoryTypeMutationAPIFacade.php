<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\Facades;

use PoPSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PostCategoryTypeMutationAPIFacade
{
    public static function getInstance(): PostCategoryTypeMutationAPIInterface
    {
        /**
         * @var PostCategoryTypeMutationAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PostCategoryTypeMutationAPIInterface::class);
        return $service;
    }
}
