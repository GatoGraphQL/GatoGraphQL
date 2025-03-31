<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\CategoryMetaMutations\Exception\CustomPostMetaCRUDMutationException;
use PoPCMSSchema\CategoryMetaMutations\TypeAPIs\CategoryMetaTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMetaMutationsWP\TypeAPIs\CustomPostMetaTypeMutationAPI;
use PoPCMSSchema\CustomPostMetaMutations\Exception\CustomPostMetaCRUDMutationException;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CategoryMetaTypeMutationAPI extends CustomPostMetaTypeMutationAPI implements CategoryMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws CustomPostMetaCRUDMutationException If there was an error
     */
    public function setCustomPostMeta(
        string|int $customPostID,
        array $entries,
    ): void {
        $this->setCustomPostMeta($customPostID, $entries);
    }

    /**
     * @return int The term_id of the newly created term
     * @throws CustomPostMetaCRUDMutationException If there was an error
     */
    public function addCustomPostMeta(
        string|int $customPostID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int {
        return $this->addCustomPostMeta($customPostID, $key, $value, $single);
    }

    /**
     * @phpstan-return class-string<CustomPostMetaCRUDMutationException>
     */
    protected function getCustomPostMetaCRUDMutationExceptionClass(): string
    {
        return CustomPostMetaCRUDMutationException::class;
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    public function updateCustomPostMeta(
        string|int $customPostID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): string|int|bool {
        return $this->updateCustomPostMeta($customPostID, $key, $value, $prevValue);
    }

    public function deleteCustomPostMeta(
        string|int $customPostID,
        string $key,
    ): void {
        $this->deleteCustomPostMeta($customPostID, $key);
    }
}
