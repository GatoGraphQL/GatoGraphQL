<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutationsWP\TypeAPIs;

use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyTermCRUDMutationException;
use PoPCMSSchema\TaxonomyMutations\TypeAPIs\TaxonomyTypeMutationAPIInterface;
use PoPCMSSchema\SchemaCommonsWP\TypeAPIs\TypeMutationAPITrait;
use PoP\ComponentModel\App;
use PoP\Root\Services\BasicServiceTrait;
use WP_Error;

use function wp_delete_term;
use function wp_insert_term;
use function wp_update_term;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TaxonomyTypeMutationAPI implements TaxonomyTypeMutationAPIInterface
{
    use BasicServiceTrait;
    use TypeMutationAPITrait;

    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * @param array<string,mixed> $query
     * @return array<string,mixed> $query
     */
    protected function convertTaxonomiesMutationQuery(array $query): array
    {
        // Convert the parameters
        if (isset($query['id'])) {
            // Nothing to do
            // $query['id'] = $query['id'];
            // unset($query['id']);
        }
        if (isset($query['taxonomy-name'])) {
            // Nothing to do
            // $query['taxonomy-name'] = $query['taxonomy-name'];
            // unset($query['taxonomy-name']);
        }
        if (isset($query['name'])) {
            // Nothing to do
            // $query['name'] = $query['name'];
            // unset($query['name']);
        }
        if (isset($query['slug'])) {
            // Nothing to do
            // $query['slug'] = $query['slug'];
            // unset($query['slug']);
        }

        /**
         * If parent-id is `null` then remove the parent!
         */
        if (array_key_exists('parent-id', $query)) {
            $query['parent'] = $query['parent-id'];
            unset($query['parent-id']);
        }

        if (isset($query['description'])) {
            // Nothing to do
            // $query['description'] = $query['description'];
            // unset($query['description']);
        }

        return App::applyFilters(
            self::HOOK_QUERY,
            $query
        );
    }
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created taxonomy
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    public function createTaxonomyTerm(string $taxonomyName, array $data): string|int
    {
        // Convert the parameters
        $data = $this->convertTaxonomiesMutationQuery($data);
        $taxonomyTermName = $data['name'] ?? '';
        $taxonomyDataOrError = wp_insert_term($taxonomyTermName, $taxonomyName, $data);
        if ($taxonomyDataOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $taxonomyDataOrError;
            throw $this->createTaxonomyTermCRUDMutationException($wpError);
        }
        /** @var int */
        $taxonomyTermID = $taxonomyDataOrError['term_id'];
        return $taxonomyTermID;
    }

    protected function createTaxonomyTermCRUDMutationException(WP_Error $wpError): TaxonomyTermCRUDMutationException
    {
        return new TaxonomyTermCRUDMutationException(
            $wpError->get_error_message(),
            $wpError->get_error_code() ? $wpError->get_error_code() : null,
            $this->getWPErrorData($wpError),
        );
    }

    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated taxonomy
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    public function updateTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName, array $data): string|int
    {
        // Convert the parameters
        $data = $this->convertTaxonomiesMutationQuery($data);
        $taxonomyDataOrError = wp_update_term((int) $taxonomyTermID, $taxonomyName, $data);
        if ($taxonomyDataOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $taxonomyDataOrError;
            throw $this->createTaxonomyTermCRUDMutationException($wpError);
        }
        /** @var int */
        $taxonomyTermID = $taxonomyDataOrError['term_id'];
        return $taxonomyTermID;
    }

    /**
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName): bool
    {
        $taxonomyDataOrError = wp_delete_term((int) $taxonomyTermID, $taxonomyName);
        if ($taxonomyDataOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $taxonomyDataOrError;
            throw $this->createTaxonomyTermCRUDMutationException($wpError);
        }
        if ($taxonomyDataOrError === 0) {
            return false;
        }
        /** @var bool $taxonomyDataOrError */
        return $taxonomyDataOrError;
    }
}
