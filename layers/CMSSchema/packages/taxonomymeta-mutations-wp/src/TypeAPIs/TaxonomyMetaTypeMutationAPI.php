<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;
use PoPCMSSchema\SchemaCommonsWP\TypeAPIs\TypeMutationAPITrait;
use PoP\Root\Services\AbstractBasicService;
use WP_Error;

use function wp_delete_term;
use function wp_insert_term;
use function wp_update_term;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TaxonomyMetaTypeMutationAPI extends AbstractBasicService implements TaxonomyMetaTypeMutationAPIInterface
{
    use TypeMutationAPITrait;
    
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created taxonomy
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    public function addTaxonomyTermMeta(string $taxonomyName, array $data): string|int
    {
        $taxonomyTermName = $data['name'] ?? '';
        $taxonomyDataOrError = wp_insert_term($taxonomyTermName, $taxonomyName, $data);
        if ($taxonomyDataOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $taxonomyDataOrError;
            throw $this->addTaxonomyTermMetaMetaCRUDMutationException($wpError);
        }
        /** @var int */
        $taxonomyTermID = $taxonomyDataOrError['term_id'];
        return $taxonomyTermID;
    }

    protected function addTaxonomyTermMetaMetaCRUDMutationException(WP_Error $wpError): TaxonomyTermMetaCRUDMutationException
    {
        return new TaxonomyTermMetaCRUDMutationException(
            $wpError->get_error_message(),
            $wpError->get_error_code() ? $wpError->get_error_code() : null,
            $this->getWPErrorData($wpError),
        );
    }

    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated taxonomy
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    public function updateTaxonomyTermMeta(string|int $taxonomyTermID, string $taxonomyName, array $data): string|int
    {
        $taxonomyDataOrError = wp_update_term((int) $taxonomyTermID, $taxonomyName, $data);
        if ($taxonomyDataOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $taxonomyDataOrError;
            throw $this->addTaxonomyTermMetaMetaCRUDMutationException($wpError);
        }
        /** @var int */
        $taxonomyTermID = $taxonomyDataOrError['term_id'];
        return $taxonomyTermID;
    }

    /**
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteTaxonomyTermMeta(string|int $taxonomyTermID, string $taxonomyName): bool
    {
        $taxonomyDataOrError = wp_delete_term((int) $taxonomyTermID, $taxonomyName);
        if ($taxonomyDataOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $taxonomyDataOrError;
            throw $this->addTaxonomyTermMetaMetaCRUDMutationException($wpError);
        }
        if ($taxonomyDataOrError === 0) {
            return false;
        }
        /** @var bool $taxonomyDataOrError */
        return $taxonomyDataOrError;
    }
}
