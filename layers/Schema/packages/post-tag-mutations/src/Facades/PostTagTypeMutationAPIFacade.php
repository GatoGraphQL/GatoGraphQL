<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\Facades;

use PoP\Engine\App;
use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;

class PostTagTypeMutationAPIFacade
{
    public static function getInstance(): PostTagTypeMutationAPIInterface
    {
        /**
         * @var PostTagTypeMutationAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(PostTagTypeMutationAPIInterface::class);
        return $service;
    }
}
