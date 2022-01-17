<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\Facades;

use PoP\Root\App;
use PoPSchema\CommentMutations\TypeAPIs\CommentTypeMutationAPIInterface;

class CommentTypeMutationAPIFacade
{
    public static function getInstance(): CommentTypeMutationAPIInterface
    {
        /**
         * @var CommentTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(CommentTypeMutationAPIInterface::class);
        return $service;
    }
}
