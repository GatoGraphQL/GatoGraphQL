<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\CommentMutations\TypeAPIs\CommentTypeMutationAPIInterface;

class CommentTypeMutationAPIFacade
{
    public static function getInstance(): CommentTypeMutationAPIInterface
    {
        /**
         * @var CommentTypeMutationAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(CommentTypeMutationAPIInterface::class);
        return $service;
    }
}
