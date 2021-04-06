<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\Facades;

use PoPSchema\CommentMutations\TypeAPIs\CommentTypeMutationAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CommentTypeMutationAPIFacade
{
    public static function getInstance(): CommentTypeMutationAPIInterface
    {
        /**
         * @var CommentTypeMutationAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(CommentTypeMutationAPIInterface::class);
        return $service;
    }
}
