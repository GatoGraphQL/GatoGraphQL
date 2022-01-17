<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\Facades;

use PoP\Root\App;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;

class UserRoleTypeAPIFacade
{
    public static function getInstance(): UserRoleTypeAPIInterface
    {
        /**
         * @var UserRoleTypeAPIInterface
         */
        $service = App::getContainer()->get(UserRoleTypeAPIInterface::class);
        return $service;
    }
}
