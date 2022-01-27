<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\Hooks;

use PoPCMSSchema\UserRolesAccessControl\Helpers\UserRoleHelper;
use PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups;

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
