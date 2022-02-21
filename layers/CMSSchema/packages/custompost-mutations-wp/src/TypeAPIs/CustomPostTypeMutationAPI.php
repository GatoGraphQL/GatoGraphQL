<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutationsWP\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;
use PoPCMSSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use WP_Error;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostTypeMutationAPI implements CustomPostTypeMutationAPIInterface
{
    use BasicServiceTrait;

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
     * @param array<string, mixed> $data
     * @return string|int the ID of the created custom post
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    public function createCustomPost(array $data): string | int
    {
        // Convert the parameters
        $this->convertQueryArgsFromPoPToCMSForInsertUpdatePost($data);
        $postIDOrError = \wp_insert_post($data, true);
        if ($postIDOrError instanceof WP_Error) {
            /** @var WP_Error */
            $error = $postIDOrError;
            throw new CustomPostCRUDMutationException(
                $error->get_error_message()
            );
        }
        /** @var int */
        $postID = $postIDOrError;
        return $postID;
    }

    /**
     * @param array<string, mixed> $data
     * @return string|int the ID of the updated custom post
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exists)
     */
    public function updateCustomPost(array $data): string | int
    {
        // Convert the parameters
        $this->convertQueryArgsFromPoPToCMSForInsertUpdatePost($data);
        $postIDOrError = \wp_update_post($data, true);
        if ($postIDOrError instanceof WP_Error) {
            /** @var WP_Error */
            $error = $postIDOrError;
            throw new CustomPostCRUDMutationException(
                $error->get_error_message()
            );
        }
        /** @var int */
        $postID = $postIDOrError;
        return $postID;
    }

    public function canUserEditCustomPost(string | int $userID, string | int $customPostID): bool
    {
        return \user_can($userID, 'edit_post', $customPostID);
    }
}
