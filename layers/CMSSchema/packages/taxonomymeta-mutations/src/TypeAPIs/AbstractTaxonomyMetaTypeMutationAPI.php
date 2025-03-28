<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs;

use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;
use PoP\Root\Services\AbstractBasicService;

abstract class AbstractTaxonomyMetaTypeMutationAPI extends AbstractBasicService implements TaxonomyMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]> $entries
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
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
    ): int {
        return $this->updateTaxonomyTermMeta(
            $taxonomyTermID,
            $key,
            $value,
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
