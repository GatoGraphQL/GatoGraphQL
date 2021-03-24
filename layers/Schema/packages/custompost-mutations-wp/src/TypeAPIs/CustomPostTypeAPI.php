<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutationsWP\TypeAPIs;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\Engine\Facades\ErrorHandling\ErrorHelperFacade;
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
     * @return string|int|null the ID of the created custom post, or null if there was an error
     */
    public function createCustomPost(array $data): string | int | null | Error
    {
        // Convert the parameters
        $this->convertQueryArgsFromPoPToCMSForInsertUpdatePost($data);
        $postIDOrError = \wp_insert_post($data);
        $errorHelper = ErrorHelperFacade::getInstance();
        return $errorHelper->returnResultOrConvertError($postIDOrError);
    }
    /**
     * @param array<string, mixed> $data
     * @return string|int|null the ID of the updated custom post, or null if the post doesn't exist
     */
    public function updateCustomPost(array $data): string | int | null | Error
    {
        // Convert the parameters
        $this->convertQueryArgsFromPoPToCMSForInsertUpdatePost($data);
        $postIDOrError = \wp_update_post($data);
        $errorHelper = ErrorHelperFacade::getInstance();
        return $errorHelper->returnResultOrConvertError($postIDOrError);
    }
    public function canUserEditCustomPost(string | int $userID, string | int $customPostID): bool
    {
        return \user_can($userID, 'edit_post', $customPostID);
    }
}
