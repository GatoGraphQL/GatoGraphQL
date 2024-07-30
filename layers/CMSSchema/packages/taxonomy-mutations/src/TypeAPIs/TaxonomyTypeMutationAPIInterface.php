<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeAPIs;

use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyTermCRUDMutationException;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface TaxonomyTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created taxonomy
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    public function createTaxonomyTerm(array $data): string|int;
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated taxonomy
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    public function updateTaxonomyTerm(array $data): string|int;
    public function canUserEditTaxonomy(string|int $userID, string|int $taxonomyID): bool;
}
