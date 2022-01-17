<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\Hooks;

use PoPCMSSchema\UserStateAccessControl\ConfigurationEntries\UserStates;

class DisableFieldIfUserIsLoggedInConfigurableAccessControlForFieldsInPrivateSchemaHookSet extends AbstractUserStateConfigurableAccessControlForFieldsInPrivateSchemaHookSet
{
    protected function removeFieldNameBasedOnUserState(string $entryValue, bool $isUserLoggedIn): bool
    {
        // Remove if the user is not logged in and, by configuration, he/she must be
        return $isUserLoggedIn && UserStates::OUT == $entryValue;
    }
}
