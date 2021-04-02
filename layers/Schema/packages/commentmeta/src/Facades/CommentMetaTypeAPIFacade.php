<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\Facades;

use PoPSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CommentMetaTypeAPIFacade
{
    public static function getInstance(): CommentMetaTypeAPIInterface
    {
        /**
         * @var CommentMetaTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(CommentMetaTypeAPIInterface::class);
        return $service;
    }
}
