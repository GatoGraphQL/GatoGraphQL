<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\Hooks;

use PoPSchema\UserRolesAccessControl\Helpers\UserRoleHelper;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoPSchema\UserRolesAccessControl\Hooks\AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet;

class MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRolePrivateSchemaHookSet extends AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet
{
    /**
     * Configuration entries
     */
    protected function getConfigurationEntries(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForDirectives(AccessControlGroups::ROLES);
    }

    /**
     * Indicate if the user has the item, to be implemented
     *
     * @param string $item
     */
    protected function doesCurrentUserHaveAnyItem(array $roles): bool
    {
        return UserRoleHelper::doesCurrentUserHaveAnyRole($roles);
    }
}
