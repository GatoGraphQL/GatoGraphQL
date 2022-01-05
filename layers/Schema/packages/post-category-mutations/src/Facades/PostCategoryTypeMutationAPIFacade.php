<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;

class PostCategoryTypeMutationAPIFacade
{
    public static function getInstance(): PostCategoryTypeMutationAPIInterface
    {
        /**
         * @var PostCategoryTypeMutationAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(PostCategoryTypeMutationAPIInterface::class);
        return $service;
    }
}
