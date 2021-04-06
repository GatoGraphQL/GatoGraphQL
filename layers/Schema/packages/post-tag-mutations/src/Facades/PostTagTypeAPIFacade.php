<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\Facades;

use PoPSchema\PostTagMutations\TypeAPIs\PostTagTypeAPIInterface;
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
