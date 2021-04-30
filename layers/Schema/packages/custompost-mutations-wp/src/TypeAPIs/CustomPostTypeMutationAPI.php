<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutationsWP\TypeAPIs;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Engine\Facades\ErrorHandling\ErrorHelperFacade;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPIUtils;
use PoPSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostTypeMutationAPI implements CustomPostTypeMutationAPIInterface
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
        // If the returned ID is 0, the creation failed
        if ($postIDOrError === 0) {
            $translationAPI = TranslationAPIFacade::getInstance();
            return new Error(
                'add-custompost-error',
                $translationAPI->__('Could not create the custom post', 'custompost-mutations-wp')
            );
        }
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
