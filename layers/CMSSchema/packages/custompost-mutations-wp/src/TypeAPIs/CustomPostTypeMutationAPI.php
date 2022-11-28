<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutationsWP\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;
use PoPCMSSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use stdClass;
use WP_Error;

use function user_can;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostTypeMutationAPI implements CustomPostTypeMutationAPIInterface
{
    use BasicServiceTrait;

    /**
     * @param array<string,mixed> $query
     */
    protected function convertQueryArgsFromPoPToCMSForInsertUpdatePost(array &$query): void
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
        if (isset($query['title'])) {
            $query['post_title'] = $query['title'];
            unset($query['title']);
        }
        if (isset($query['custompost-type'])) {
            $query['post_type'] = $query['custompost-type'];
            unset($query['custompost-type']);
        }
    }
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created custom post
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    public function createCustomPost(array $data): string|int
    {
        // Convert the parameters
        $this->convertQueryArgsFromPoPToCMSForInsertUpdatePost($data);
        $postIDOrError = \wp_insert_post($data, true);
        if ($postIDOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $postIDOrError;
            throw $this->createCustomPostCRUDMutationException($wpError);
        }
        /** @var int */
        $postID = $postIDOrError;
        return $postID;
    }

    protected function createCustomPostCRUDMutationException(WP_Error $wpError): CustomPostCRUDMutationException
    {
        /** @var stdClass|null */
        $errorData = null;
        if ($wpError->get_error_data()) {
            if (is_array($wpError->get_error_data())) {
                $errorData = (object) $wpError->get_error_data();
            } else {
                $errorData = new stdClass();
                $key = $wpError->get_error_code() ? (string) $wpError->get_error_code() : 'data';
                $errorData->$key = $wpError->get_error_data();
            }
        }
        return new CustomPostCRUDMutationException(
            $wpError->get_error_message(),
            $wpError->get_error_code() ? $wpError->get_error_code() : null,
            $errorData,
        );
    }

    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated custom post
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    public function updateCustomPost(array $data): string|int
    {
        // Convert the parameters
        $this->convertQueryArgsFromPoPToCMSForInsertUpdatePost($data);
        $postIDOrError = \wp_update_post($data, true);
        if ($postIDOrError instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $postIDOrError;
            throw $this->createCustomPostCRUDMutationException($wpError);
        }
        /** @var int */
        $postID = $postIDOrError;
        return $postID;
    }

    public function canUserEditCustomPost(string|int $userID, string|int $customPostID): bool
    {
        return user_can((int)$userID, 'edit_post', $customPostID);
    }
}
