<?php

declare(strict_types=1);

namespace PoPSchema\Comments\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;

class CommentTypeAPIFacade
{
    public static function getInstance(): CommentTypeAPIInterface
    {
        /**
         * @var CommentTypeAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(CommentTypeAPIInterface::class);
        return $service;
    }
}
