<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutationsWP\TypeAPIs;

use PoPCMSSchema\SchemaCommonsWP\TypeAPIs\TypeMutationAPITrait;
use PoPCMSSchema\UserStateMutations\Exception\UserStateMutationException;
use PoPCMSSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;
use PoP\Root\Services\AbstractBasicService;
use stdClass;
use WP_Error;

use function wp_logout;
use function wp_set_current_user;
use function wp_signon;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserStateTypeMutationAPI extends AbstractBasicService implements UserStateTypeMutationAPIInterface
{
    use TypeMutationAPITrait;

    /**
     * @throws UserStateMutationException In case of error
     * @param array<string,mixed> $credentials
     */
    public function login(array $credentials): object
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
        $result = wp_signon($credentials);

        if ($result instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $result;
            throw $this->createUserStateMutationException(
                $wpError,
                $credentials,
            );
        }

        $user = $result;
        wp_set_current_user($user->ID);
        return $user;
    }

    /**
     * @param array<string,mixed> $credentials
     */
    protected function createUserStateMutationException(
        WP_Error $wpError,
        array $credentials,
    ): UserStateMutationException {
        $errorCode = $wpError->get_error_code() ? $wpError->get_error_code() : null;
        $errorMessage = $wpError->get_error_message();

        /**
         * Override the messages to remove HTML tags and links
         */
        $errorMessage = match ($errorCode) {
            'invalid_username' => sprintf(
                $this->__('The username \'%s\' is not registered on this site.', 'user-state-mutations'),
                $credentials['user_login']
            ),
            'incorrect_password' => sprintf(
                $this->__('The password you entered for the username \'%s\' is incorrect.', 'user-state-mutations'),
                $credentials['user_login']
            ),
            default => $errorMessage,
        };

        $errorData = $this->getWPErrorData($wpError) ?? new stdClass();
        $errorData->userLogin = $credentials['user_login'];
        return new UserStateMutationException(
            $errorMessage,
            $errorCode,
            $errorData,
        );
    }

    public function logout(): void
    {
        wp_logout();

        // Delete the current user, so that it already says "user not logged in" for the toplevel feedback
        global $current_user;
        $current_user = null;
        wp_set_current_user(0);
    }
}
