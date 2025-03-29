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
        string|int $termID,
        array $entries,
    ): void;

    /**
     * @return int The term_id of the newly created term
     * @throws TermMetaCRUDMutationException If there was an error
     */
    public function addTermMeta(
        string|int $termID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int;
    
    /**
     * @throws TermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTermMeta(
        string|int $termID,
        string $key,
        mixed $value,
    ): void;

    /**
     * @throws TermMetaCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteTermMeta(
        string|int $termID,
        string $key,
    ): void;
}
