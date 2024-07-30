<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutationsWP\TypeAPIs;

use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyTermCRUDMutationException;
use PoPCMSSchema\TaxonomyMutations\TypeAPIs\TaxonomyTypeMutationAPIInterface;
use PoPCMSSchema\SchemaCommonsWP\TypeAPIs\TypeMutationAPITrait;
use PoP\ComponentModel\App;
use PoP\Root\Services\BasicServiceTrait;
use WP_Error;

use function user_can;
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
            $query['term_id'] = $query['id'];
            unset($query['id']);
        }
        if (isset($query['taxonomy-name'])) {
            $query['taxonomy'] = $query['taxonomy-name'];
            unset($query['taxonomy-name']);
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
        if (isset($query['parent-id'])) {
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
    public function createTaxonomyTerm(array $data): string|int
    {
        // Convert the parameters
        $data = $this->convertTaxonomiesMutationQuery($data);
        $name = $data['name'] ?? '';
        $taxonomy = $data['taxonomy'] ?? '';
        $taxonomyDataOrError = wp_insert_term($name, $taxonomy, $data);
        if ($taxonomyDataOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $taxonomyDataOrError;
            throw $this->createTaxonomyTermTermCRUDMutationException($wpError);
        }
        /** @var int */
        $taxonomyTermID = $taxonomyDataOrError['term_id'];
        return $taxonomyTermID;
    }

    protected function createTaxonomyTermTermCRUDMutationException(WP_Error $wpError): TaxonomyTermCRUDMutationException
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
    public function updateTaxonomyTerm(array $data): string|int
    {
        // Convert the parameters
        $data = $this->convertTaxonomiesMutationQuery($data);
        $taxonomyTermID = $data['term_id'] ?? null;
        $taxonomy = $data['taxonomy'] ?? '';
        $taxonomyDataOrError = wp_update_term($taxonomyTermID, $taxonomy, $data);
        if ($taxonomyDataOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $taxonomyDataOrError;
            throw $this->createTaxonomyTermTermCRUDMutationException($wpError);
        }
        /** @var int */
        $taxonomyTermID = $taxonomyDataOrError['term_id'];
        return $taxonomyTermID;
    }
}
