<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\Hooks;

use PoP\ComponentModel\State\ApplicationState;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups;
use PoP\AccessControl\Hooks\AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet;

abstract class AbstractUserStateConfigurableAccessControlForDirectivesInPrivateSchemaHookSet extends AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet
{
    protected function enabled(): bool
    {
        $vars = ApplicationState::getVars();
        $isUserLoggedIn = $vars['global-userstate']['is-user-logged-in'];
        return parent::enabled() && $this->enableBasedOnUserState($isUserLoggedIn);
    }

    abstract protected function enableBasedOnUserState(bool $isUserLoggedIn): bool;

    /**
     * Configuration entries
     *
     * @return array
     */
    protected function getConfigurationEntries(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForDirectives(AccessControlGroups::STATE);
    }
}
