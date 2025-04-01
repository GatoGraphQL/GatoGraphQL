<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeAPIs;

use PoPCMSSchema\MetaMutations\Exception\EntityMetaCRUDMutationException;

interface EntityMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws EntityMetaCRUDMutationException If there was an error
     */
    public function setEntityMeta(
        string|int $entityID,
        array $entries,
    ): void;

    /**
     * @return int The term_id of the newly created term
     * @throws EntityMetaCRUDMutationException If there was an error
     */
    public function addEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int;

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws EntityMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
    ): string|int|bool;

    /**
     * @throws EntityMetaCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteEntityMeta(
        string|int $entityID,
        string $key,
    ): void;
}
