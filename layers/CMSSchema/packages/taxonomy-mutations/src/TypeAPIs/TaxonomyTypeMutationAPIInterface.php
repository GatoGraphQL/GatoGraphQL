<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeAPIs;

use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyTermCRUDMutationException;

interface TaxonomyTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created taxonomy
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    public function createTaxonomyTerm(string $taxonomyName, array $data): string|int;
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated taxonomy
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName, array $data): string|int;
    /**
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName): bool;
}
