<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\Facades;

use PoP\Engine\App;
use PoPSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;

class UserRoleTypeAPIFacade
{
    public static function getInstance(): UserRoleTypeAPIInterface
    {
        /**
         * @var UserRoleTypeAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(UserRoleTypeAPIInterface::class);
        return $service;
    }
}
