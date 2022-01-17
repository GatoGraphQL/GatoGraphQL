<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\Facades;

use PoP\Root\App;
use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;

class PostCategoryTypeAPIFacade
{
    public static function getInstance(): PostCategoryTypeAPIInterface
    {
        /**
         * @var PostCategoryTypeAPIInterface
         */
        $service = App::getContainer()->get(PostCategoryTypeAPIInterface::class);
        return $service;
    }
}
