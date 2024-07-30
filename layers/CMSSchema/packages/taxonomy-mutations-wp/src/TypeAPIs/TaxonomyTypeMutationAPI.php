<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutationsWP\TypeAPIs;

use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyCRUDMutationException;
use PoPCMSSchema\TaxonomyMutations\TypeAPIs\TaxonomyTypeMutationAPIInterface;
use PoPCMSSchema\SchemaCommonsWP\TypeAPIs\TypeMutationAPITrait;
use PoP\ComponentModel\App;
use PoP\Root\Services\BasicServiceTrait;
use WP_Error;

use function user_can;

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
        if (isset($query['status'])) {
            $query['post_status'] = $query['status'];
            unset($query['status']);
        }
        if (isset($query['id'])) {
            $query['ID'] = $query['id'];
            unset($query['id']);
        }
        if (isset($query['content'])) {
            $query['post_content'] = $query['content'];
            unset($query['content']);
        }
        if (isset($query['excerpt'])) {
            $query['post_excerpt'] = $query['excerpt'];
            unset($query['excerpt']);
        }
        if (isset($query['slug'])) {
            $query['post_name'] = $query['slug'];
            unset($query['slug']);
        }
        if (isset($query['title'])) {
            $query['post_title'] = $query['title'];
            unset($query['title']);
        }
        if (isset($query['custompost-type'])) {
            $query['post_type'] = $query['custompost-type'];
            unset($query['custompost-type']);
        }

        return App::applyFilters(
            self::HOOK_QUERY,
            $query
        );
    }
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created taxonomy
     * @throws TaxonomyCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    public function createTaxonomy(array $data): string|int
    {
        // Convert the parameters
        $data = $this->convertTaxonomiesMutationQuery($data);
        $postIDOrError = \wp_insert_post($data, true);
        if ($postIDOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $postIDOrError;
            throw $this->createTaxonomyCRUDMutationException($wpError);
        }
        /** @var int */
        $postID = $postIDOrError;
        return $postID;
    }

    protected function createTaxonomyCRUDMutationException(WP_Error $wpError): TaxonomyCRUDMutationException
    {
        return new TaxonomyCRUDMutationException(
            $wpError->get_error_message(),
            $wpError->get_error_code() ? $wpError->get_error_code() : null,
            $this->getWPErrorData($wpError),
        );
    }

    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated taxonomy
     * @throws TaxonomyCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    public function updateTaxonomy(array $data): string|int
    {
        // Convert the parameters
        $data = $this->convertTaxonomiesMutationQuery($data);
        $postIDOrError = \wp_update_post($data, true);
        if ($postIDOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $postIDOrError;
            throw $this->createTaxonomyCRUDMutationException($wpError);
        }
        /** @var int */
        $postID = $postIDOrError;
        return $postID;
    }

    public function canUserEditTaxonomy(string|int $userID, string|int $taxonomyID): bool
    {
        return user_can((int)$userID, 'edit_post', $taxonomyID);
    }
}
