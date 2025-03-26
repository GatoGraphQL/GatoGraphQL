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
     * @return int The term_id of the newly created term
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error
     */
    public function addTaxonomyTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int {
        $result = add_term_meta((int) $taxonomyTermID, $key, $value, $single);
        $this->handleMaybeError($result);
        return $result;
    }

    protected function handleMaybeError(
        int|false|WP_Error $result,
    ): void {
        if ($result === false) {
            /** @var WP_Error */
            $wpError = $result;
            throw $this->getTaxonomyTermMetaCRUDMutationException($wpError);
        }
        if ($result instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $result;
            throw $this->getTaxonomyTermMetaCRUDMutationException($wpError);
        }
    }

    protected function getTaxonomyTermMetaCRUDMutationException(WP_Error $wpError): TaxonomyTermMetaCRUDMutationException
    {
        return new TaxonomyTermMetaCRUDMutationException(
            $wpError->get_error_message(),
            $wpError->get_error_code() ? $wpError->get_error_code() : null,
            $this->getWPErrorData($wpError),
        );
    }

    /**
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTaxonomyTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
    ): int {
        $taxonomyDataOrError = wp_update_term((int) $taxonomyTermID, $taxonomyName, $data);
        if ($taxonomyDataOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $taxonomyDataOrError;
            throw $this->getTaxonomyTermMetaCRUDMutationException($wpError);
        }
        /** @var int */
        $taxonomyTermID = $taxonomyDataOrError['term_id'];
        return $taxonomyTermID;
    }

    /**
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteTaxonomyTermMeta(
        string|int $taxonomyTermID,
        string $key,
    ): void {
        $taxonomyDataOrError = wp_delete_term((int) $taxonomyTermID, $taxonomyName);
        if ($taxonomyDataOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $taxonomyDataOrError;
            throw $this->getTaxonomyTermMetaCRUDMutationException($wpError);
        }
        if ($taxonomyDataOrError === 0) {
            return false;
        }
        /** @var bool $taxonomyDataOrError */
        return $taxonomyDataOrError;
    }
}
