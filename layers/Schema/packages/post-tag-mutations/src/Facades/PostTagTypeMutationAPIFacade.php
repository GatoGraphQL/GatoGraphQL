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
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(PostTagTypeMutationAPIInterface::class);
        return $service;
    }
}
