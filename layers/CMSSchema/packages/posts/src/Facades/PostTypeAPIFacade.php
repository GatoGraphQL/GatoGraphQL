<?php

declare(strict_types=1);

namespace PoPSchema\Posts\Facades;

use PoP\Root\App;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;

class PostTypeAPIFacade
{
    public static function getInstance(): PostTypeAPIInterface
    {
        /**
         * @var PostTypeAPIInterface
         */
        $service = App::getContainer()->get(PostTypeAPIInterface::class);
        return $service;
    }
}
