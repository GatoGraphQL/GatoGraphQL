<?php

declare(strict_types=1);

namespace PoPSchema\Users\Facades;

use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class UserTypeAPIFacade
{
    public static function getInstance(): UserTypeAPIInterface
    {
        /**
         * @var UserTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(UserTypeAPIInterface::class);
        return $service;
    }
}
