<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\Facades;

use PoP\Root\App;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;

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
