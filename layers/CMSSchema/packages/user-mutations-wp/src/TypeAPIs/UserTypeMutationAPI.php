<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutationsWP\TypeAPIs;

use PoPCMSSchema\UserMutations\Exception\UserCRUDMutationException;
use PoPCMSSchema\UserMutations\TypeAPIs\UserTypeMutationAPIInterface;
use PoP\ComponentModel\App;
use PoP\Root\Services\AbstractBasicService;
use WP_Error;
use WP_User;

use function current_user_can;
use function get_role;
use function is_email;
use function is_multisite;
use function is_wp_error;
use function user_can;
use function wp_delete_user;
use function wp_insert_user;
use function wp_send_new_user_notifications;
use function wp_slash;
use function wp_update_user;

class UserTypeMutationAPI extends AbstractBasicService implements UserTypeMutationAPIInterface
{
    public function canLoggedInUserEditUser(string|int $userID): bool
    {
        $loggedInUserID = App::getState('current-user-id');
        return ((int) $loggedInUserID === (int) $userID)
            || user_can((int)$loggedInUserID, 'edit_users');
    }

    public function canLoggedInUserCreateUsers(): bool
    {
        return current_user_can('create_users');
    }

    public function canLoggedInUserDeleteUsers(): bool
    {
        return current_user_can('delete_users');
    }

    public function canLoggedInUserDeleteUser(string|int $userID): bool
    {
        return current_user_can('delete_user', (int) $userID);
    }

    public function canLoggedInUserPromoteUsers(): bool
    {
        return current_user_can('promote_users');
    }

    public function isMultisite(): bool
    {
        return is_multisite();
    }

    public function roleExists(string $role): bool
    {
        return get_role($role) !== null;
    }

    public function isValidEmail(string $email): bool
    {
        return is_email($email) !== false;
    }

    /**
     * @throws UserCRUDMutationException In case of error
     * @param array<string,mixed> $userData
     */
    public function createUser(
        array $userData,
    ): string|int {
        $this->assertNotMultisite();

        /** @var string[]|null */
        $roles = $userData['roles'] ?? null;
        /** @var bool */
        $sendEmailNotification = $userData['sendEmailNotification'] ?? false;

        $args = $this->convertUserData($userData);

        $userIDOrError = wp_insert_user(wp_slash($args));
        if (is_wp_error($userIDOrError)) {
            /** @var WP_Error */
            $wpError = $userIDOrError;
            throw new UserCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        /** @var int */
        $userID = $userIDOrError;

        $this->applyUserRoles($userID, $roles);

        if ($sendEmailNotification) {
            wp_send_new_user_notifications($userID, 'user');
        }

        return $userID;
    }

    /**
     * @throws UserCRUDMutationException In case of error
     * @param array<string,mixed> $userData
     */
    public function updateUser(
        array $userData,
    ): string|int {
        $this->assertNotMultisite();

        /** @var string|int */
        $userID = $userData['id'];
        /** @var string[]|null */
        $roles = $userData['roles'] ?? null;
        $rolesProvided = array_key_exists('roles', $userData);

        $args = $this->convertUserData($userData);
        $args['ID'] = $userID;

        $userIDOrError = wp_update_user(wp_slash($args));
        if (is_wp_error($userIDOrError)) {
            /** @var WP_Error */
            $wpError = $userIDOrError;
            throw new UserCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        if ($rolesProvided) {
            $this->applyUserRoles($userID, $roles);
        }

        return $userID;
    }

    /**
     * @throws UserCRUDMutationException In case of error
     */
    public function deleteUser(
        string|int $userID,
        string|int|null $reassignAuthorContentToUserID,
    ): void {
        $this->assertNotMultisite();

        // `wp_delete_user` lives in the admin includes
        // @phpstan-ignore-next-line
        require_once ABSPATH . 'wp-admin/includes/user.php';

        $reassign = $reassignAuthorContentToUserID === null
            ? null
            : (int) $reassignAuthorContentToUserID;

        $deleted = wp_delete_user((int) $userID, $reassign);
        if (!$deleted) {
            throw new UserCRUDMutationException(
                sprintf(
                    $this->__('The user with ID \'%s\' could not be deleted', 'gatographql'),
                    $userID
                )
            );
        }
    }

    /**
     * User CRUD mutations are not supported on a multisite network,
     * where the user model (network-wide users, per-blog roles) differs.
     *
     * @throws UserCRUDMutationException If running on a multisite network
     */
    protected function assertNotMultisite(): void
    {
        if (!$this->isMultisite()) {
            return;
        }
        throw new UserCRUDMutationException(
            $this->__('User mutations are not supported on a multisite network', 'gatographql')
        );
    }

    /**
     * @param string[]|null $roles
     */
    protected function applyUserRoles(
        string|int $userID,
        ?array $roles,
    ): void {
        if ($roles === null) {
            return;
        }
        $user = new WP_User((int) $userID);
        if ($roles === []) {
            $user->set_role('');
            return;
        }
        $user->set_role($roles[0]);
        foreach (array_slice($roles, 1) as $role) {
            $user->add_role($role);
        }
    }

    /**
     * Convert the input data into the args expected by
     * `wp_insert_user` / `wp_update_user`.
     *
     * @param array<string,mixed> $userData
     * @return array<string,mixed>
     */
    protected function convertUserData(array $userData): array
    {
        $args = [];
        foreach (
            [
            'username' => 'user_login',
            'email' => 'user_email',
            'password' => 'user_pass',
            'slug' => 'user_nicename',
            'websiteURL' => 'user_url',
            'displayName' => 'display_name',
            'firstName' => 'first_name',
            'lastName' => 'last_name',
            'nickname' => 'nickname',
            'description' => 'description',
            'locale' => 'locale',
            'registeredDate' => 'user_registered',
            ] as $inputKey => $wpKey
        ) {
            if (!array_key_exists($inputKey, $userData)) {
                continue;
            }
            $args[$wpKey] = $userData[$inputKey];
        }
        return $args;
    }
}
