<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\Hooks;

use PoPSchema\UserRolesAccessControl\Helpers\UserRoleHelper;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;

class MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRolePrivateSchemaHookSet extends AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet
{
    /**
     * Configuration entries
     */
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForDirectives(AccessControlGroups::ROLES);
    }

    /**
     * Indicate if the user has the item, to be implemented
     *
     * @param mixed[] $roles
     */
    protected function doesCurrentUserHaveAnyItem(array $roles): bool
    {
        return UserRoleHelper::doesCurrentUserHaveAnyRole($roles);
    }
}
