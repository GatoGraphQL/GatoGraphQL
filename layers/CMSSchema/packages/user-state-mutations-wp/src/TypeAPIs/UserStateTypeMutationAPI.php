<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutationsWP\TypeAPIs;

use PoPCMSSchema\UserStateMutations\Exception\UserStateMutationException;
use PoPCMSSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;
use PoP\Root\Services\BasicServiceTrait;
use WP_Error;

use function wp_logout;
use function wp_set_current_user;
use function wp_signon;
use stdClass;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserStateTypeMutationAPI implements UserStateTypeMutationAPIInterface
{
    use BasicServiceTrait;

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
            throw new $this->createUserStateMutationException($wpError);
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
        if ($wpError->get_error_code() === 'incorrect_password') {
            throw new UserStateMutationException(
                sprintf(
                    $this->__('The password you entered for the username \'%s\' is incorrect.', 'user-state-mutations'),
                    $credentials['user_login']
                ),
                $wpError->get_error_code(),
            );
        }
            
            /** @var stdClass|null */
        $errorData = null;
        if ($wpError->get_error_data()) {
            if (is_array($wpError->get_error_data())) {
                $errorData = (object) $wpError->get_error_data();
            } else {
                $errorData = new stdClass();
                $key = $wpError->get_error_code() ? (string) $wpError->get_error_code() : 'data';
                $errorData->$key = $wpError->get_error_data();
            }
        }
        return new UserStateMutationException(
            $wpError->get_error_message(),
            $wpError->get_error_code() ? $wpError->get_error_code() : null,
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
