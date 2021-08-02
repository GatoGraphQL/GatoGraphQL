<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\Facades;

use PoPSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class UserRoleTypeAPIFacade
{
    public static function getInstance(): UserRoleTypeAPIInterface
    {
        /**
         * @var UserRoleTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(UserRoleTypeAPIInterface::class);
        return $service;
    }
}
