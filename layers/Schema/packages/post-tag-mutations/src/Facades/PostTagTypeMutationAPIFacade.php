<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;

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
