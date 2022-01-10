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
        $state['global-userstate'] = [];
        $userStateTypeAPI = UserStateTypeAPIFacade::getInstance();
        if ($userStateTypeAPI->isUserLoggedIn()) {
            $state['global-userstate']['is-user-logged-in'] = true;
            $state['global-userstate']['current-user'] = $userStateTypeAPI->getCurrentUser();
            $state['global-userstate']['current-user-id'] = $userStateTypeAPI->getCurrentUserID();
        } else {
            $state['global-userstate']['is-user-logged-in'] = false;
            $state['global-userstate']['current-user'] = null;
            $state['global-userstate']['current-user-id'] = null;
        }
    }
}
