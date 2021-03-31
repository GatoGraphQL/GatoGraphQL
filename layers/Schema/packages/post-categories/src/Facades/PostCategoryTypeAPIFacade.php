<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\Facades;

use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PostCategoryTypeAPIFacade
{
    public static function getInstance(): PostCategoryTypeAPIInterface
    {
        /**
         * @var PostCategoryTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PostCategoryTypeAPIInterface::class);
        return $service;
    }
}
