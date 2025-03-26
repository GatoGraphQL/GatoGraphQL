<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs;

use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;

interface TaxonomyMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created taxonomy
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    public function addTaxonomyTermMeta(string $taxonomyName, array $data): string|int;
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated taxonomy
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTaxonomyTermMeta(string|int $taxonomyTermID, string $taxonomyName, array $data): string|int;
    /**
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteTaxonomyTermMeta(string|int $taxonomyTermID, string $taxonomyName): bool;
}
