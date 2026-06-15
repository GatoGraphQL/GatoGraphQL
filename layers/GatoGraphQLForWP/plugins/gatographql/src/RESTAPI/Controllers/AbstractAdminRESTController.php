<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\RESTAPI\Controllers;

use function current_user_can;

/**
 * Base class for REST controllers that may only be used by an admin.
 * The capability defaults to the one for managing the GraphQL schema,
 * and can be overridden by downstream plugins.
 */
abstract class AbstractAdminRESTController extends AbstractRESTController
{
    /**
     * Validate the user has the required capability.
     */
    public function checkPermission(): bool
    {
        return current_user_can($this->getCapability());
    }

    protected function getCapability(): string
    {
        return (string) constant('GATOGRAPHQL_CAPABILITY_MANAGE_GRAPHQL_SCHEMA');
    }
}
