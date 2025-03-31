<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeAPIs;

use PoPCMSSchema\MetaMutations\Exception\TermMetaCRUDMutationException;

interface MetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws TermMetaCRUDMutationException If there was an error
     */
    public function setTermMeta(
        string|int $entityID,
        array $entries,
    ): void;

    /**
     * @return int The term_id of the newly created term
     * @throws TermMetaCRUDMutationException If there was an error
     */
    public function addTermMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int;

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws TermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTermMeta(
        string|int $entityID,
        string $key,
        mixed $value,
    ): string|int|bool;

    /**
     * @throws TermMetaCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteTermMeta(
        string|int $entityID,
        string $key,
    ): void;
}
