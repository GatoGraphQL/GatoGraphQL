<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeAPIs;

use PoPCMSSchema\MetaMutations\TypeAPIs\EntityMetaTypeMutationAPIInterface;
use PoPCMSSchema\UserMetaMutations\Exception\UserMetaCRUDMutationException;

interface UserMetaTypeMutationAPIInterface extends EntityMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws UserMetaCRUDMutationException If there was an error
     */
    public function setUserMeta(
        string|int $userID,
        array $entries,
    ): void;

    /**
     * @return int The term_id of the newly created term
     * @throws UserMetaCRUDMutationException If there was an error
     */
    public function addUserMeta(
        string|int $userID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int;

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws UserMetaCRUDMutationException If there was an error (eg: user does not exist)
     */
    public function updateUserMeta(
        string|int $userID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): string|int|bool;

    /**
     * @throws UserMetaCRUDMutationException If there was an error (eg: user does not exist)
     */
    public function deleteUserMeta(
        string|int $userID,
        string $key,
        mixed $value = null,
    ): void;
}
