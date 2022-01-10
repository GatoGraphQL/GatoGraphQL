<?php

declare(strict_types=1);

namespace PoPSchema\UserState\State;

use PoPSchema\UserState\Facades\UserStateTypeAPIFacade;

class ApplicationStateUtils
{
    /**
     * Add the user's (non)logged-in state
     *
     * @param array<string, mixed> $state
     */
    public static function setUserStateVars(array &$state): void
    {
        $userStateTypeAPI = UserStateTypeAPIFacade::getInstance();
        if ($userStateTypeAPI->isUserLoggedIn()) {
            $state['is-user-logged-in'] = true;
            $state['current-user'] = $userStateTypeAPI->getCurrentUser();
            $state['current-user-id'] = $userStateTypeAPI->getCurrentUserID();
        } else {
            $state['is-user-logged-in'] = false;
            $state['current-user'] = null;
            $state['current-user-id'] = null;
        }
    }
}
