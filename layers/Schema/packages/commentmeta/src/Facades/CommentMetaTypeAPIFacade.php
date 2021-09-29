<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;

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
