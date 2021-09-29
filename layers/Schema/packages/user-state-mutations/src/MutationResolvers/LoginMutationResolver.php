<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\UserState\State\ApplicationStateUtils;
use PoPSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class LoginMutationResolver extends AbstractMutationResolver
{
    protected UserTypeAPIInterface $userTypeAPI;
    protected UserStateTypeMutationAPIInterface $userStateTypeMutationAPI;

    #[Required]
    public function autowireLoginMutationResolver(
        UserTypeAPIInterface $userTypeAPI,
        UserStateTypeMutationAPIInterface $userStateTypeMutationAPI,
    ): void {
        $this->userTypeAPI = $userTypeAPI;
        $this->userStateTypeMutationAPI = $userStateTypeMutationAPI;
    }

    public function validateErrors(array $form_data): ?array
    {
        $errors = [];
        $username_or_email = $form_data[MutationInputProperties::USERNAME_OR_EMAIL];
        $pwd = $form_data[MutationInputProperties::PASSWORD];

        if (!$username_or_email) {
            $errors[] = $this->translationAPI->__('Please supply your username or email', 'user-state-mutations');
        }
        if (!$pwd) {
            $errors[] = $this->translationAPI->__('Please supply your password', 'user-state-mutations');
        }

        $vars = ApplicationState::getVars();
        if ($vars['global-userstate']['is-user-logged-in']) {
            $errors[] = $this->getUserAlreadyLoggedInErrorMessage($vars['global-userstate']['current-user-id']);
        }
        return $errors;
    }

    protected function getUserAlreadyLoggedInErrorMessage(string | int $user_id): string
    {
        return $this->translationAPI->__('You are already logged in', 'user-state-mutations');
    }

    public function executeMutation(array $form_data): mixed
    {
        // If the user is already logged in, then return the error
        $username_or_email = $form_data[MutationInputProperties::USERNAME_OR_EMAIL];
        $pwd = $form_data[MutationInputProperties::PASSWORD];

        // Find out if it was a username or an email that was provided
        $is_email = strpos($username_or_email, '@');
        if ($is_email) {
            $user = $this->userTypeAPI->getUserByEmail($username_or_email);
            if (!$user) {
                return new Error(
                    'no-user',
                    $this->translationAPI->__('There is no user registered with that email address.')
                );
            }
            $username = $this->userTypeAPI->getUserLogin($user);
        } else {
            $username = $username_or_email;
        }

        $credentials = array(
            'login' => $username,
            'password' => $pwd,
            'remember' => true,
        );
        $loginResult = $this->userStateTypeMutationAPI->login($credentials);

        if (GeneralUtils::isError($loginResult)) {
            return $loginResult;
        }

        $user = $loginResult;

        // Modify the routing-state with the newly logged in user info
        ApplicationStateUtils::setUserStateVars(ApplicationState::$vars);

        $userID = $this->userTypeAPI->getUserId($user);
        $this->hooksAPI->doAction('gd:user:loggedin', $userID);
        return $userID;
    }
}
