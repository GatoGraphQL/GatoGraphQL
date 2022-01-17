<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\Hooks;

use PoPSchema\UserRolesAccessControl\Helpers\UserRoleHelper;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;

class MaybeDisableDirectivesIfLoggedInUserDoesNotHaveCapabilityPrivateSchemaHookSet extends AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet
{
    /**
     * Configuration entries
     */
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForDirectives(AccessControlGroups::CAPABILITIES);
    }

    /**
     * Indicate if the user has the item, to be implemented
     *
     * @param mixed[] $capabilities
     */
    protected function doesCurrentUserHaveAnyItem(array $capabilities): bool
    {
        return UserRoleHelper::doesCurrentUserHaveAnyCapability($capabilities);
    }
}
