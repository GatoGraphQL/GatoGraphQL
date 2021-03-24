<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\UserState\State\ApplicationStateUtils;
use PoPSchema\UserStateMutations\Facades\UserStateTypeAPIFacade;

class LogoutMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    public function validateErrors(array $form_data): ?array
    {
        $errors = [];
        $this->validateUserIsLoggedIn($errors);
        return $errors;
    }
    public function execute(array $form_data): mixed
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
