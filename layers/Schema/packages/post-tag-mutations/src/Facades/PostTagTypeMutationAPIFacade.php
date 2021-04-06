<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\Facades;

use PoPSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PostTagTypeMutationAPIFacade
{
    public static function getInstance(): PostTagTypeMutationAPIInterface
    {
        /**
         * @var PostTagTypeMutationAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PostTagTypeMutationAPIInterface::class);
        return $service;
    }
}
