<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;

class UserRoleTypeAPIFacade
{
    public static function getInstance(): UserRoleTypeAPIInterface
    {
        /**
         * @var UserRoleTypeAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(UserRoleTypeAPIInterface::class);
        return $service;
    }
}
