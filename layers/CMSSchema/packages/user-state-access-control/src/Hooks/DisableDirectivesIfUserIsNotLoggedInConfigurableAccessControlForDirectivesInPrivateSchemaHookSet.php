<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\Hooks;

use PoPCMSSchema\UserStateAccessControl\ConfigurationEntries\UserStates;

class DisableDirectivesIfUserIsNotLoggedInConfigurableAccessControlForDirectivesInPrivateSchemaHookSet extends AbstractUserStateConfigurableAccessControlForDirectivesInPrivateSchemaHookSet
{
    protected function enableBasedOnUserState(bool $isUserLoggedIn): bool
    {
        return !$isUserLoggedIn;
    }

    protected function getRequiredEntryValue(): ?string
    {
        return UserStates::IN;
    }
}
