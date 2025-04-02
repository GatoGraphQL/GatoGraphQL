<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\CommentMetaMutations\TypeAPIs\CommentMetaTypeMutationAPIInterface;

class CommentMetaTypeMutationAPIFacade
{
    public static function getInstance(): CommentMetaTypeMutationAPIInterface
    {
        /**
         * @var CommentMetaTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(CommentMetaTypeMutationAPIInterface::class);
        return $service;
    }
}
