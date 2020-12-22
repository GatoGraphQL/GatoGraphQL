<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\Facades;

use PoPSchema\CommentMutations\TypeAPIs\CommentTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CommentTypeAPIFacade
{
    public static function getInstance(): CommentTypeAPIInterface
    {
        /**
         * @var CommentTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(CommentTypeAPIInterface::class);
        return $service;
    }
}
