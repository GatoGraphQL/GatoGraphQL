<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\Roles;

use function current_user_can;

abstract class AbstractAdminRESTController extends AbstractRESTController
{
    protected function getControllerNamespace(): string
    {
        return 'admin';
    }

    /**
     * Validate the user is the admin.
     */
    public function checkAdminPermission(): bool
    {
        return current_user_can(Roles::ADMINISTRATOR);
    }
}
