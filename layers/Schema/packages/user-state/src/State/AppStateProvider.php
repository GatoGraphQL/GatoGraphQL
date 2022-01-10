<?php

declare(strict_types=1);

namespace PoPSchema\UserState\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPSchema\UserState\TypeAPIs\UserStateTypeAPIInterface;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?UserStateTypeAPIInterface $userStateTypeAPI = null;

    final public function setUserStateTypeAPI(UserStateTypeAPIInterface $userStateTypeAPI): void
    {
        $this->userStateTypeAPI = $userStateTypeAPI;
    }
    final protected function getUserStateTypeAPI(): UserStateTypeAPIInterface
    {
        return $this->userStateTypeAPI ??= $this->instanceManager->getInstance(UserStateTypeAPIInterface::class);
    }
    
    public function initialize(array &$state): void
    {
        $this->setUserStateVars($state);
    }

    /**
     * Add the user's (non)logged-in state
     *
     * @param array<string, mixed> $state
     */
    public function setUserStateVars(array &$state): void
    {
        if ($this->getUserStateTypeAPI()->isUserLoggedIn()) {
            $state['is-user-logged-in'] = true;
            $state['current-user'] = $this->getUserStateTypeAPI()->getCurrentUser();
            $state['current-user-id'] = $this->getUserStateTypeAPI()->getCurrentUserID();
            return;
        }
        $state['is-user-logged-in'] = false;
        $state['current-user'] = null;
        $state['current-user-id'] = null;
    }
}
