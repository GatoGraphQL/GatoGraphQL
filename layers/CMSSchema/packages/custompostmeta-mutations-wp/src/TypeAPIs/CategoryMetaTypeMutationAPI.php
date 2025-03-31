<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\CategoryMetaMutations\Exception\CategoryTermMetaCRUDMutationException;
use PoPCMSSchema\CategoryMetaMutations\TypeAPIs\CategoryMetaTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMetaMutationsWP\TypeAPIs\TaxonomyMetaTypeMutationAPI;
use PoPCMSSchema\TaxonomyMetaMutations\Exception\CustomPostMetaCRUDMutationException;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CategoryMetaTypeMutationAPI extends TaxonomyMetaTypeMutationAPI implements CategoryMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws CategoryTermMetaCRUDMutationException If there was an error
     */
    public function setCategoryTermMeta(
        string|int $customPostID,
        array $entries,
    ): void {
        $this->setCustomPostMeta($customPostID, $entries);
    }

    /**
     * @return int The term_id of the newly created term
     * @throws CategoryTermMetaCRUDMutationException If there was an error
     */
    public function addCategoryTermMeta(
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
        return CategoryTermMetaCRUDMutationException::class;
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws CategoryTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateCategoryTermMeta(
        string|int $customPostID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): string|int|bool {
        return $this->updateCustomPostMeta($customPostID, $key, $value, $prevValue);
    }

    public function deleteCategoryTermMeta(
        string|int $customPostID,
        string $key,
    ): void {
        $this->deleteCustomPostMeta($customPostID, $key);
    }
}
