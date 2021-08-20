<?php

declare(strict_types=1);

namespace PoPSchema\Media\ConditionalOnComponent\Users\Facades;

use PoPSchema\Media\ConditionalOnComponent\Users\TypeAPIs\UserMediaTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class UserMediaTypeAPIFacade
{
    public static function getInstance(): UserMediaTypeAPIInterface
    {
        /**
         * @var UserMediaTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(UserMediaTypeAPIInterface::class);
        return $service;
    }
}
