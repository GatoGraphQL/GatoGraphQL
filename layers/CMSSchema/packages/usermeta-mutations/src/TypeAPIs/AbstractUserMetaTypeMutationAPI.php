<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeAPIs;

use PoPCMSSchema\UserMetaMutations\Exception\UserMetaCRUDMutationException;
use PoPCMSSchema\UserMetaMutations\TypeAPIs\UserMetaTypeMutationAPIInterface;
use PoPCMSSchema\MetaMutations\TypeAPIs\AbstractEntityMetaTypeMutationAPI;

abstract class AbstractUserMetaTypeMutationAPI extends AbstractEntityMetaTypeMutationAPI implements UserMetaTypeMutationAPIInterface
{
    /**
     * @phpstan-return class-string<UserMetaCRUDMutationException>
     */
    protected function getEntityMetaCRUDMutationExceptionClass(): string
    {
        return UserMetaCRUDMutationException::class;
    }

    /**
     * @param array<string,mixed[]|null> $entries
     * @throws UserMetaCRUDMutationException If there was an error
     */
    public function setUserMeta(
        string|int $userID,
        array $entries,
    ): void {
        $this->setEntityMeta(
            $userID,
            $entries,
        );
    }

    /**
     * @return int The term_id of the newly created term
     * @throws UserMetaCRUDMutationException If there was an error
     */
    public function addUserMeta(
        string|int $userID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int {
        return $this->addEntityMeta(
            $userID,
            $key,
            $value,
            $single,
        );
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws UserMetaCRUDMutationException If there was an error (eg: user does not exist)
     */
    public function updateUserMeta(
        string|int $userID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): string|int|bool {
        return $this->updateEntityMeta(
            $userID,
            $key,
            $value,
            $prevValue,
        );
    }

    /**
     * @throws UserMetaCRUDMutationException If there was an error (eg: user does not exist)
     */
    public function deleteUserMeta(
        string|int $userID,
        string $key,
        mixed $value = null,
    ): void {
        $this->deleteEntityMeta(
            $userID,
            $key,
            $value,
        );
    }
}
