<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\CategoryMetaMutations\Exception\CategoryTermMetaCRUDMutationException;
use PoPCMSSchema\CategoryMetaMutations\TypeAPIs\CategoryMetaTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMutationsWP\TypeAPIs\TaxonomyTypeMutationAPI;
use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyTermCRUDMutationException;
use WP_Error;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CategoryMetaTypeMutationAPI extends TaxonomyTypeMutationAPI implements CategoryMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created category
     * @throws CategoryTermMetaCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    public function createCategoryTerm(string $taxonomyName, array $data): string|int
    {
        return $this->createTaxonomyTerm($taxonomyName, $data);
    }

    protected function createTaxonomyTermCRUDMutationException(WP_Error $wpError): TaxonomyTermCRUDMutationException
    {
        return new CategoryTermMetaCRUDMutationException(
            $wpError->get_error_message(),
            $wpError->get_error_code() ? $wpError->get_error_code() : null,
            $this->getWPErrorData($wpError),
        );
    }

    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated category
     * @throws CategoryTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateCategoryTerm(string|int $taxonomyTermID, string $taxonomyName, array $data): string|int
    {
        return $this->updateTaxonomyTerm($taxonomyTermID, $taxonomyName, $data);
    }

    public function deleteCategoryTerm(string|int $taxonomyTermID, string $taxonomyName): bool
    {
        return $this->deleteTaxonomyTerm($taxonomyTermID, $taxonomyName);
    }
}
