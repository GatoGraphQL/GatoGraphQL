<?php

declare(strict_types=1);

namespace PoPSchema\Comments\Facades;

use PoP\Root\App;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;

class CommentTypeAPIFacade
{
    public static function getInstance(): CommentTypeAPIInterface
    {
        /**
         * @var CommentTypeAPIInterface
         */
        $service = App::getContainer()->get(CommentTypeAPIInterface::class);
        return $service;
    }
}
