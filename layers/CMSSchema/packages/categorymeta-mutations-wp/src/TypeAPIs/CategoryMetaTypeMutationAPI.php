<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\CategoryMetaMutations\Exception\CategoryTermMetaCRUDMutationException;
use PoPCMSSchema\CategoryMetaMutations\TypeAPIs\CategoryMetaTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMetaMutationsWP\TypeAPIs\TaxonomyMetaTypeMutationAPI;
use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;
use WP_Error;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CategoryMetaTypeMutationAPI extends TaxonomyMetaTypeMutationAPI implements CategoryMetaTypeMutationAPIInterface
{
    /**
     * @throws CategoryTermMetaCRUDMutationException If there was an error
     */
    public function addCategoryTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
        bool $single = false,
    ): void {
        return $this->addTaxonomyTermMeta($taxonomyTermID, $key, $value, $single);
    }

    protected function addTaxonomyTermMetaMetaCRUDMutationException(WP_Error $wpError): TaxonomyTermMetaCRUDMutationException
    {
        return new CategoryTermMetaCRUDMutationException(
            $wpError->get_error_message(),
            $wpError->get_error_code() ? $wpError->get_error_code() : null,
            $this->getWPErrorData($wpError),
        );
    }

    /**
     * @throws CategoryTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateCategoryTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
    ): void {
        return $this->updateTaxonomyTermMeta($taxonomyTermID, $key, $value);
    }

    public function deleteCategoryTermMeta(
        string|int $taxonomyTermID,
        string $key,
    ): void {
        return $this->deleteTaxonomyTermMeta($taxonomyTermID, $key);
    }
}
