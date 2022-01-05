<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ConditionalOnComponent\Users\Facades;

use PoP\Engine\App;
use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Comments\ConditionalOnComponent\Users\TypeAPIs\CommentTypeAPIInterface;

class CommentTypeAPIFacade
{
    public static function getInstance(): CommentTypeAPIInterface
    {
        /**
         * @var CommentTypeAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(CommentTypeAPIInterface::class);
        return $service;
    }
}
