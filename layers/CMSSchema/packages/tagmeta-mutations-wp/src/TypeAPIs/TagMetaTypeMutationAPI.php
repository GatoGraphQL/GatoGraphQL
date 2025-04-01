<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\TagMetaMutations\Exception\TagTermMetaCRUDMutationException;
use PoPCMSSchema\TagMetaMutations\TypeAPIs\TagMetaTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMetaMutationsWP\TypeAPIs\TaxonomyMetaTypeMutationAPI;
use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TagMetaTypeMutationAPI extends TaxonomyMetaTypeMutationAPI implements TagMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws TagTermMetaCRUDMutationException If there was an error
     */
    public function setTagTermMeta(
        string|int $taxonomyTermID,
        array $entries,
    ): void {
        $this->setTaxonomyTermMeta($taxonomyTermID, $entries);
    }

    /**
     * @return int The term_id of the newly created term
     * @throws TagTermMetaCRUDMutationException If there was an error
     */
    public function addTagTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int {
        return $this->addTaxonomyTermMeta($taxonomyTermID, $key, $value, $single);
    }

    /**
     * @phpstan-return class-string<TaxonomyTermMetaCRUDMutationException>
     */
    protected function getTaxonomyTermMetaCRUDMutationExceptionClass(): string
    {
        return TagTermMetaCRUDMutationException::class;
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws TagTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTagTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): string|int|bool {
        return $this->updateTaxonomyTermMeta($taxonomyTermID, $key, $value, $prevValue);
    }

    public function deleteTagTermMeta(
        string|int $taxonomyTermID,
        string $key,
    ): void {
        $this->deleteTaxonomyTermMeta($taxonomyTermID, $key);
    }
}
