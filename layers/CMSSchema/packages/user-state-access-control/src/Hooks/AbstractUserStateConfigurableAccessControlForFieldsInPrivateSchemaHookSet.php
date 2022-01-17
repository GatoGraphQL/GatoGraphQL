<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\Hooks;

use PoP\Root\App;
use PoP\AccessControl\Hooks\AbstractConfigurableAccessControlForFieldsInPrivateSchemaHookSet;
use PoPCMSSchema\UserStateAccessControl\Services\AccessControlGroups;

abstract class AbstractUserStateConfigurableAccessControlForFieldsInPrivateSchemaHookSet extends AbstractConfigurableAccessControlForFieldsInPrivateSchemaHookSet
{
    /**
     * Configuration entries
     */
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForFields(AccessControlGroups::STATE);
    }

    protected function removeFieldNameBasedOnMatchingEntryValue($entryValue = null): bool
    {
        // Obtain the user state: logged in or not
        $isUserLoggedIn = App::getState('is-user-logged-in');
        // Let the implementation class decide if to remove the field or not
        return $this->removeFieldNameBasedOnUserState((string)$entryValue, $isUserLoggedIn);
    }

    abstract protected function removeFieldNameBasedOnUserState(string $entryValue, bool $isUserLoggedIn): bool;
}
