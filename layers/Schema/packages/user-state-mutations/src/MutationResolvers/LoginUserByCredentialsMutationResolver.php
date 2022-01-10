<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\Root\App;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\UserStateMutations\StaticHelpers\AppStateHelpers;
use PoPSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;

class LoginUserByCredentialsMutationResolver extends AbstractMutationResolver
{
    private ?UserTypeAPIInterface $userTypeAPI = null;
    private ?UserStateTypeMutationAPIInterface $userStateTypeMutationAPI = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }
    final public function setUserStateTypeMutationAPI(UserStateTypeMutationAPIInterface $userStateTypeMutationAPI): void
    {
        $this->userStateTypeMutationAPI = $userStateTypeMutationAPI;
    }
    final protected function getUserStateTypeMutationAPI(): UserStateTypeMutationAPIInterface
    {
        return $this->userStateTypeMutationAPI ??= $this->instanceManager->getInstance(UserStateTypeMutationAPIInterface::class);
    }

    public function validateErrors(array $formData): array
    {
        $errors = [];
        $username_or_email = $formData[MutationInputProperties::USERNAME_OR_EMAIL];
        $pwd = $formData[MutationInputProperties::PASSWORD];

        if (!$username_or_email) {
            $errors[] = $this->__('Please supply your username or email', 'user-state-mutations');
        }
        if (!$pwd) {
            $errors[] = $this->__('Please supply your password', 'user-state-mutations');
        }

        if (App::getState('is-user-logged-in')) {
            $errors[] = $this->getUserAlreadyLoggedInErrorMessage(App::getState('current-user-id'));
        }
        return $errors;
    }

    protected function getUserAlreadyLoggedInErrorMessage(string | int $user_id): string
    {
        return $this->__('You are already logged in', 'user-state-mutations');
    }

    public function executeMutation(array $formData): mixed
    {
        // If the user is already logged in, then return the error
        $username_or_email = $formData[MutationInputProperties::USERNAME_OR_EMAIL];
        $pwd = $formData[MutationInputProperties::PASSWORD];

        // Find out if it was a username or an email that was provided
        $is_email = strpos($username_or_email, '@');
        if ($is_email) {
            $user = $this->getUserTypeAPI()->getUserByEmail($username_or_email);
            if (!$user) {
                return new Error(
                    'no-user',
                    $this->__('There is no user registered with that email address.')
                );
            }
            $username = $this->getUserTypeAPI()->getUserLogin($user);
        } else {
            $username = $username_or_email;
        }

        $credentials = array(
            'login' => $username,
            'password' => $pwd,
            'remember' => true,
        );
        $loginResult = $this->getUserStateTypeMutationAPI()->login($credentials);

        if (GeneralUtils::isError($loginResult)) {
            return $loginResult;
        }

        $user = $loginResult;

        // Modify the routing-state with the newly logged in user info
        AppStateHelpers::resetCurrentUserInAppState();

        $userID = $this->getUserTypeAPI()->getUserId($user);
        $this->getHooksAPI()->doAction('gd:user:loggedin', $userID);
        return $userID;
    }
}
