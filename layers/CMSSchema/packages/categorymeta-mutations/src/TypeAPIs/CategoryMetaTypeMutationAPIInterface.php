<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeAPIs;

use PoPCMSSchema\CategoryMetaMutations\Exception\CategoryTermCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CategoryMetaTypeMutationAPIInterface extends TaxonomyMetaTypeMutationAPIInterface
{
    /**
     * @throws CategoryTermMetaCRUDMutationException If there was an error
     */
    public function addCategoryTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
        bool $single = false,
    ): void;
    
    /**
     * @throws CategoryTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
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
