<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeAPIs;

use PoPCMSSchema\UserMutations\Exception\UserCRUDMutationException;

interface UserTypeMutationAPIInterface
{
    public function canLoggedInUserEditUser(string|int $userID): bool;
    public function canLoggedInUserCreateUsers(): bool;
    public function canLoggedInUserDeleteUsers(): bool;
    public function canLoggedInUserDeleteUser(string|int $userID): bool;
    public function canLoggedInUserPromoteUsers(): bool;

    /**
     * Whether the site runs on a multisite network, where the user
     * CRUD semantics differ and are not supported by these mutations.
     */
    public function isMultisite(): bool;

    public function roleExists(string $role): bool;
    public function isValidEmail(string $email): bool;

    /**
     * @throws UserCRUDMutationException In case of error
     * @param array<string,mixed> $userData
     */
    public function createUser(
        array $userData,
    ): string|int;

    /**
     * @throws UserCRUDMutationException In case of error
     * @param array<string,mixed> $userData
     */
    public function updateUser(
        array $userData,
    ): string|int;

    /**
     * @throws UserCRUDMutationException In case of error
     * @param string|int|null $reassignAuthorContentToUserID User to reassign the deleted user's content to, or `null` to delete the content
     */
    public function deleteUser(
        string|int $userID,
        string|int|null $reassignAuthorContentToUserID,
    ): void;
}
