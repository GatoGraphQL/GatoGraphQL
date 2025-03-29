<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeAPIs;

use PoPCMSSchema\CategoryMetaMutations\Exception\CategoryTermMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CategoryMetaTypeMutationAPIInterface extends TaxonomyMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws CategoryTermMetaCRUDMutationException If there was an error
     */
    public function setCategoryTermMeta(
        string|int $taxonomyTermID,
        array $entries,
    ): void;

    /**
     * @return int The term_id of the newly created term
     * @throws CategoryTermMetaCRUDMutationException If there was an error
     */
    public function addCategoryTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int;
    
    /**
     * @throws CategoryTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateCategoryTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
    ): void;

    public function deleteCategoryTermMeta(
        string|int $taxonomyTermID,
        string $key,
    ): void;
}
