<?php

declare(strict_types=1);

namespace PoPSchema\Posts\Facades;

use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PostTypeAPIFacade
{
    public static function getInstance(): PostTypeAPIInterface
    {
        /**
         * @var PostTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PostTypeAPIInterface::class);
        return $service;
    }
}
