<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs;

use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;
use PoP\Root\Services\AbstractBasicService;

abstract class AbstractTaxonomyMetaTypeMutationAPI extends AbstractBasicService implements TaxonomyMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error
     */
    public function setTermMeta(
        string|int $taxonomyTermID,
        array $entries,
    ): void {
        $this->setTaxonomyTermMeta(
            $taxonomyTermID,
            $entries,
        );
    }

    /**
     * @return int The term_id of the newly created term
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error
     */
    public function addTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int {
        return $this->addTaxonomyTermMeta(
            $taxonomyTermID,
            $key,
            $value,
            $single,
        );
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): string|int|bool {
        return $this->updateTaxonomyTermMeta(
            $taxonomyTermID,
            $key,
            $value,
            $prevValue,
        );
    }

    /**
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteTermMeta(
        string|int $taxonomyTermID,
        string $key,
    ): void {
        $this->deleteTaxonomyTermMeta(
            $taxonomyTermID,
            $key,
        );
    }
}
