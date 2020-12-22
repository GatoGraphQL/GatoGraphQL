<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\Facades;

use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

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
