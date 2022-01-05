<?php

declare(strict_types=1);

namespace PoPSchema\Posts\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;

class PostTypeAPIFacade
{
    public static function getInstance(): PostTypeAPIInterface
    {
        /**
         * @var PostTypeAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(PostTypeAPIInterface::class);
        return $service;
    }
}
