<?php

declare(strict_types=1);

namespace PoPSchema\UserState\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoP\Engine\FieldResolvers\OperatorGlobalFieldResolver;
use PoPSchema\UserState\State\ApplicationStateUtils;

class VarsHooks extends AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addAction(
            'ApplicationState:addVars',
            array($this, 'addVars'),
            10,
            1
        );
        $this->hooksAPI->addAction(
            OperatorGlobalFieldResolver::HOOK_SAFEVARS,
            [$this, 'setSafeVars'],
            10,
            1
        );
    }

    /**
     * @param array<array> $vars_in_array
     */
    public function setSafeVars(array $vars_in_array): void
    {
        // Remove the current user object
        $safeVars = &$vars_in_array[0];
        unset($safeVars['global-userstate']['current-user']);
    }

    /**
     * Add the user's (non)logged-in state
     *
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;
        ApplicationStateUtils::setUserStateVars($vars);
    }
}
