<?php

declare(strict_types=1);

namespace PoPSchema\UserState\State;

use PoPSchema\UserState\Facades\UserStateTypeDataResolverFacade;

class ApplicationStateUtils
{
    /**
     * Add the user's (non)logged-in state
     *
     * @param array<string, mixed> $vars
     */
    public static function setUserStateVars(array &$vars): void
    {
        $vars['global-userstate'] = [];
        $userStateTypeDataResolver = UserStateTypeDataResolverFacade::getInstance();
        if ($userStateTypeDataResolver->isUserLoggedIn()) {
            $vars['global-userstate']['is-user-logged-in'] = true;
            $vars['global-userstate']['current-user'] = $userStateTypeDataResolver->getCurrentUser();
            $vars['global-userstate']['current-user-id'] = $userStateTypeDataResolver->getCurrentUserID();
        } else {
            $vars['global-userstate']['is-user-logged-in'] = false;
            $vars['global-userstate']['current-user'] = null;
            $vars['global-userstate']['current-user-id'] = null;
        }
    }
}
