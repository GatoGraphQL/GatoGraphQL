<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\Facades;

use PoP\Root\App;
use PoPSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;

class CommentMetaTypeAPIFacade
{
    public static function getInstance(): CommentMetaTypeAPIInterface
    {
        /**
         * @var CommentMetaTypeAPIInterface
         */
        $service = App::getContainer()->get(CommentMetaTypeAPIInterface::class);
        return $service;
    }
}
