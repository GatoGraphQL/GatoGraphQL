<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnComponent\Users\Facades;

use PoP\Root\App;
use PoPCMSSchema\Comments\ConditionalOnComponent\Users\TypeAPIs\CommentTypeAPIInterface;

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
