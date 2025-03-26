<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs;

use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;

interface TaxonomyMetaTypeMutationAPIInterface
{
    /**
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error
     */
    public function addTaxonomyTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
        bool $single = false,
    ): void;
    
    /**
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTaxonomyTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
    ): void;

    /**
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteTaxonomyTermMeta(
        string|int $taxonomyTermID,
        string $key,
    ): void;
}
