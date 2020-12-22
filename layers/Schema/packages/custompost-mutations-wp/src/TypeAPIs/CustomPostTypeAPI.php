<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutationsWP\TypeAPIs;

use PoPSchema\CustomPostMutations\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPIUtils;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostTypeAPI implements CustomPostTypeAPIInterface
{
    protected function convertQueryArgsFromPoPToCMSForInsertUpdatePost(array &$query): void
    {
        // Convert the parameters
        if (isset($query['status'])) {
            $query['post_status'] = CustomPostTypeAPIUtils::convertPostStatusFromPoPToCMS($query['status']);
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
     * @return mixed the ID of the created custom post
     */
    public function createCustomPost(array $data)
    {
        // Convert the parameters
        $this->convertQueryArgsFromPoPToCMSForInsertUpdatePost($data);
        return \wp_insert_post($data);
    }
    /**
     * @param array<string, mixed> $data
     * @return mixed the ID of the updated custom post
     */
    public function updateCustomPost(array $data)
    {
        // Convert the parameters
        $this->convertQueryArgsFromPoPToCMSForInsertUpdatePost($data);
        return \wp_update_post($data);
    }
    public function canUserEditCustomPost($userID, $customPostID): bool
    {
        return \user_can($userID, 'edit_post', $customPostID);
    }
}
