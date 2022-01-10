<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\Hooks;

use PoP\AccessControl\Hooks\AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups;

abstract class AbstractUserStateConfigurableAccessControlForDirectivesInPrivateSchemaHookSet extends AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet
{
    protected function enabled(): bool
    {
        $vars = ApplicationState::getVars();
        $isUserLoggedIn = $vars['is-user-logged-in'];
        return parent::enabled() && $this->enableBasedOnUserState($isUserLoggedIn);
    }

    abstract protected function enableBasedOnUserState(bool $isUserLoggedIn): bool;

    /**
     * Configuration entries
     */
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForDirectives(AccessControlGroups::STATE);
    }
}
