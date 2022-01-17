<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\Facades;

use PoP\Root\App;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;

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
