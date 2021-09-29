<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;

class PostTagTypeAPIFacade
{
    public static function getInstance(): PostTagTypeAPIInterface
    {
        /**
         * @var PostTagTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PostTagTypeAPIInterface::class);
        return $service;
    }
}
