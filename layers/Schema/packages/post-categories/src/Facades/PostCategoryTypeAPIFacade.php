<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\Facades;

use PoP\Engine\App;
use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;

class PostCategoryTypeAPIFacade
{
    public static function getInstance(): PostCategoryTypeAPIInterface
    {
        /**
         * @var PostCategoryTypeAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(PostCategoryTypeAPIInterface::class);
        return $service;
    }
}
