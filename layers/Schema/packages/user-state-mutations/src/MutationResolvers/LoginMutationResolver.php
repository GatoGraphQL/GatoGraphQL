<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\ErrorHandling\Error;
use PoPSchema\UserStateMutations\Facades\UserStateTypeAPIFacade;
use PoPSchema\UserState\State\ApplicationStateUtils;

class LoginMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(array $form_data): ?array
    {
        $errors = [];
        $username_or_email = $form_data[MutationInputProperties::USERNAME_OR_EMAIL];
        $pwd = $form_data[MutationInputProperties::PASSWORD];

        if (!$username_or_email) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Please supply your username or email', 'user-state-mutations');
        }
        if (!$pwd) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Please supply your password', 'user-state-mutations');
        }

        $vars = ApplicationState::getVars();
        if ($vars['global-userstate']['is-user-logged-in']) {
            $errors[] = $this->getUserAlreadyLoggedInErrorMessage($vars['global-userstate']['current-user-id']);
        }
        return $errors;
    }

    protected function getUserAlreadyLoggedInErrorMessage(string | int $user_id): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('You are already logged in', 'user-state-mutations');
    }

    public function execute(array $form_data): mixed
    {
        // If the user is already logged in, then return the error
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $cmsusersresolver = \PoPSchema\Users\ObjectPropertyResolverFactory::getInstance();
        $userStateTypeAPI = UserStateTypeAPIFacade::getInstance();

        $username_or_email = $form_data[MutationInputProperties::USERNAME_OR_EMAIL];
        $pwd = $form_data[MutationInputProperties::PASSWORD];

        // Find out if it was a username or an email that was provided
        $is_email = strpos($username_or_email, '@');
        if ($is_email) {
            $user = $cmsusersapi->getUserByEmail($username_or_email);
            if (!$user) {
                return new Error(
                    'no-user',
                    TranslationAPIFacade::getInstance()->__('There is no user registered with that email address.')
                );
            }
            $username = $cmsusersresolver->getUserLogin($user);
        } else {
            $username = $username_or_email;
        }

        $credentials = array(
            'login' => $username,
            'password' => $pwd,
            'remember' => true,
        );
        $loginResult = $userStateTypeAPI->login($credentials);

        if (GeneralUtils::isError($loginResult)) {
            return $loginResult;
        }

        $user = $loginResult;

        // Modify the routing-state with the newly logged in user info
        ApplicationStateUtils::setUserStateVars(ApplicationState::$vars);

        $userID = $cmsusersresolver->getUserId($user);
        HooksAPIFacade::getInstance()->doAction('gd:user:loggedin', $userID);
        return $userID;
    }
}
