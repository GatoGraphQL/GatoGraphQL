<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\Facades;

use PoP\Root\App;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;

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
