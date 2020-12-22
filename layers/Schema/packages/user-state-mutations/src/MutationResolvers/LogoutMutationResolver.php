<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\UserStateMutations\Facades\UserStateTypeAPIFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\UserState\State\ApplicationStateUtils;

class LogoutMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    public function validateErrors(array $form_data): ?array
    {
        $errors = [];
        $this->validateUserIsLoggedIn($errors);
        return $errors;
    }
    /**
     * @return mixed
     */
    public function execute(array $form_data)
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['global-userstate']['current-user-id'];

        $userStateTypeAPI = UserStateTypeAPIFacade::getInstance();
        $userStateTypeAPI->logout();

        // Modify the routing-state with the newly logged in user info
        ApplicationStateUtils::setUserStateVars(ApplicationState::$vars);

        HooksAPIFacade::getInstance()->doAction('gd:user:loggedout', $user_id);
        return $user_id;
    }
}
