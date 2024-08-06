<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutationsWP\TypeAPIs;

use PoPCMSSchema\TagMutations\Exception\TagTermCRUDMutationException;
use PoPCMSSchema\TagMutations\TypeAPIs\TagTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMutationsWP\TypeAPIs\TaxonomyTypeMutationAPI;
use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyTermCRUDMutationException;
use WP_Error;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TagTypeMutationAPI extends TaxonomyTypeMutationAPI implements TagTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created tag
     * @throws TagTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    public function createTagTerm(string $taxonomyName, array $data): string|int
    {
        return $this->createTaxonomyTerm($taxonomyName, $data);
    }

    protected function createTaxonomyTermCRUDMutationException(WP_Error $wpError): TaxonomyTermCRUDMutationException
    {
        return new TagTermCRUDMutationException(
            $wpError->get_error_message(),
            $wpError->get_error_code() ? $wpError->get_error_code() : null,
            $this->getWPErrorData($wpError),
        );
    }

    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated tag
     * @throws TagTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTagTerm(string|int $taxonomyTermID, string $taxonomyName, array $data): string|int
    {
        return $this->updateTaxonomyTerm($taxonomyTermID, $taxonomyName, $data);
    }

    public function deleteTagTerm(string|int $taxonomyTermID, string $taxonomyName): bool
    {
        return $this->deleteTaxonomyTerm($taxonomyTermID, $taxonomyName);
    }
}
