<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\CommentMutations\TypeAPIs\CommentTypeMutationAPIInterface;

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
