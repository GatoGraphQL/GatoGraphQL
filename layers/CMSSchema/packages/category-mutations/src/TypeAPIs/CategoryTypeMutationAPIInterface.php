<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeAPIs;

use PoPCMSSchema\CategoryMutations\Exception\CategoryTermCRUDMutationException;
use PoPCMSSchema\TaxonomyMutations\TypeAPIs\TaxonomyTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CategoryTypeMutationAPIInterface extends TaxonomyTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created category
     * @throws CategoryTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    public function createCategoryTerm(string $taxonomyName, array $data): string|int;
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated category
     * @throws CategoryTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateCategoryTerm(string|int $taxonomyTermID, string $taxonomyName, array $data): string|int;
    public function deleteCategoryTerm(string|int $taxonomyTermID, string $taxonomyName): bool;
}
