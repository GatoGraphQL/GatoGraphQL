<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutationsWP\TypeAPIs;

use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;
use PoPCMSSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\SchemaCommonsWP\TypeAPIs\TypeMutationAPITrait;
use PoP\ComponentModel\App;
use PoP\Root\Services\AbstractBasicService;
use WP_Error;
use WP_Post;

use function get_post_type_object;
use function user_can;
use function wp_insert_post;
use function wp_slash;
use function wp_update_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostTypeMutationAPI extends AbstractBasicService implements CustomPostTypeMutationAPIInterface
{
    use TypeMutationAPITrait;

    public const HOOK_QUERY = __CLASS__ . ':query';

    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }

    /**
     * @param array<string,mixed> $query
     * @return array<string,mixed> $query
     */
    protected function convertCustomPostsMutationQuery(array $query): array
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
        if (isset($query['parent-id'])) {
            $query['post_parent'] = $query['parent-id'];
            unset($query['parent-id']);
        }
        // Passing `0` as parent means "remove the parent", so it's already handled above
        // elseif (array_key_exists('parent-id', $query)) {
        //     // If passing `null` then remove the parent
        //     $query['post_parent'] = 0;
        //     unset($query['parent-id']);
        // }
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
        if (isset($query['parent-slug-path'])) {
            $customPostType = $query['post_type'] ?? '';
            /** @var WP_Post|null */
            $parentPost = $this->getCustomPostTypeAPI()->getCustomPostBySlugPath(
                $query['parent-slug-path'],
                $customPostType
            );
            if ($parentPost !== null) {
                $query['post_parent'] = $parentPost->ID;
            }
            unset($query['parent-slug-path']);
        }
        if (isset($query['date'])) {
            $query['post_date'] = $query['date'];
            // Also store the date for `draft` status
            $query['edit_date'] = true;
            unset($query['date']);
        }
        if (isset($query['gmtDate'])) {
            $query['post_date_gmt'] = $query['gmtDate'];
            // Also store the date for `draft` status
            $query['edit_date'] = true;
            unset($query['gmtDate']);
        }

        return App::applyFilters(
            self::HOOK_QUERY,
            $query
        );
    }
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created custom post
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    public function createCustomPost(array $data): string|int
    {
        // Convert the parameters
        $data = $this->convertCustomPostsMutationQuery($data);
        $postIDOrError = wp_insert_post(wp_slash($data), true);
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
        return new CustomPostCRUDMutationException(
            $wpError->get_error_message(),
            $wpError->get_error_code() ? $wpError->get_error_code() : null,
            $this->getWPErrorData($wpError),
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
        $data = $this->convertCustomPostsMutationQuery($data);
        $postIDOrError = wp_update_post(wp_slash($data), true);
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

    public function canUserEditCustomPostType(string|int $userID, string $customPostType): bool
    {
        $customPostTypeObject = get_post_type_object($customPostType);
        if ($customPostTypeObject === null) {
            return false;
        }

        return isset($customPostTypeObject->cap->edit_posts) && user_can((int)$userID, $customPostTypeObject->cap->edit_posts);
    }
}
