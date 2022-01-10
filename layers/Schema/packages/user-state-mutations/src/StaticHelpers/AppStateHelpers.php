<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\StaticHelpers;

use PoP\Root\App;
use PoPSchema\UserState\Facades\UserStateTypeAPIFacade;

class AppStateHelpers
{
    /**
     * Reset the user's (non)logged-in state in the application state
     */
    public static function resetCurrentUserInAppState(): void
    {
        $userStateTypeAPI = UserStateTypeAPIFacade::getInstance();
        App::getAppStateManager()->override('is-user-logged-in', $userStateTypeAPI->isUserLoggedIn());
        App::getAppStateManager()->override('current-user', $userStateTypeAPI->getCurrentUser());
        App::getAppStateManager()->override('current-user-id', $userStateTypeAPI->getCurrentUserID());
    }
}
