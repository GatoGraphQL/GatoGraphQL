<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutationsWP\TypeAPIs;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Facades\ErrorHandling\ErrorHelperFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserStateTypeMutationAPI implements UserStateTypeMutationAPIInterface
{
    /**
     * @return mixed Result or Error
     */
    public function login(array $credentials): mixed
    {
        // Convert params
        if (isset($credentials['login'])) {
            $credentials['user_login'] = $credentials['login'];
            unset($credentials['login']);
        }
        if (isset($credentials['password'])) {
            $credentials['user_password'] = $credentials['password'];
            unset($credentials['password']);
        }
        if (isset($credentials['remember'])) {
            // Same param name, so do nothing
        }
        $result = \wp_signon($credentials);

        // If it is an error, convert from WP_Error to Error
        $errorHelper = ErrorHelperFacade::getInstance();
        $result = $errorHelper->returnResultOrConvertError($result);

        // Set the current user already, so that it already says "user logged in" for the toplevel feedback
        if (GeneralUtils::isError($result)) {
            /** @var Error */
            $error = $result;
            return $this->maybeChangeErrorMessage($error, $credentials);
        }

        $user = $result;
        \wp_set_current_user($user->ID);

        return $result;
    }

    protected function maybeChangeErrorMessage(Error $error, array $credentials): Error
    {
        // Transform the error message from WordPress
        $translationAPI = TranslationAPIFacade::getInstance();
        $errorCode = $error->getCode();
        if ($errorCode === 'incorrect_password') {
            return new Error(
                'incorrect_password',
                sprintf(
                    $translationAPI->__('The password you entered for the username \'%s\' is incorrect.', 'user-state-mutations'),
                    $credentials['user_login']
                )
            );
        }

        return $error;
    }

    public function logout(): void
    {
        \wp_logout();

        // Delete the current user, so that it already says "user not logged in" for the toplevel feedback
        global $current_user;
        $current_user = null;
        \wp_set_current_user(0);
    }
}
