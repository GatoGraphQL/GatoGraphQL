<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeAPIs;

use PoPCMSSchema\MetaMutations\TypeAPIs\EntityMetaTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMetaMutations\Exception\CustomPostMetaCRUDMutationException;

interface CustomPostMetaTypeMutationAPIInterface extends EntityMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws CustomPostMetaCRUDMutationException If there was an error
     */
    public function setCustomPostMeta(
        string|int $customPostID,
        array $entries,
    ): void;

    /**
     * @return int The term_id of the newly created term
     * @throws CustomPostMetaCRUDMutationException If there was an error
     */
    public function addCustomPostMeta(
        string|int $customPostID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int;

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    public function updateCustomPostMeta(
        string|int $customPostID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): string|int|bool;

    /**
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    public function deleteCustomPostMeta(
        string|int $customPostID,
        string $key,
        mixed $value = null,
    ): void;
}
