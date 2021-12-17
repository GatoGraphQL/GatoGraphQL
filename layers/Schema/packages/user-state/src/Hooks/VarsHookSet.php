<?php

declare(strict_types=1);

namespace PoPSchema\UserState\Hooks;

use PoP\Engine\FieldResolvers\ObjectType\OperatorGlobalObjectTypeFieldResolver;
use PoP\BasicService\AbstractHookSet;
use PoPSchema\UserState\State\ApplicationStateUtils;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addAction(
            'ApplicationState:addVars',
            array($this, 'addVars'),
            10,
            1
        );
        $this->getHooksAPI()->addAction(
            OperatorGlobalObjectTypeFieldResolver::HOOK_SAFEVARS,
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
