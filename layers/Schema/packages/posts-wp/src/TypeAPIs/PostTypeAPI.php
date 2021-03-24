<?php

declare(strict_types=1);

namespace PoPSchema\PostsWP\TypeAPIs;

use WP_Post;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Posts\ComponentConfiguration;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI;

use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTypeAPI extends CustomPostTypeAPI implements PostTypeAPIInterface
{
    /**
     * Add an extra hook just to modify posts
     *
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    protected function convertCustomPostsQuery(array $query, array $options = []): array
    {
        $query = parent::convertCustomPostsQuery($query, $options);
        return HooksAPIFacade::getInstance()->applyFilters(
            'CMSAPI:posts:query',
            $query,
            $options
        );
    }

    /**
     * Indicates if the passed object is of type Post
     */
    public function isInstanceOfPostType(object $object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type == 'post';
    }

    /**
     * Get the post with provided ID or, if it doesn't exist, null
     */
    public function getPost(int | string $id): ?object
    {
        $post = get_post($id);
        if (!$post || $post->post_type != 'post') {
            return null;
        }
        return $post;
    }

    /**
     * Indicate if an post with provided ID exists
     */
    public function postExists(int | string $id): bool
    {
        return $this->getPost($id) != null;
    }

    /**
     * Limit of how many custom posts can be retrieved in the query.
     * Override this value for specific custom post types
     */
    protected function getCustomPostListMaxLimit(): int
    {
        return ComponentConfiguration::getPostListMaxLimit();
    }

    public function getPosts(array $query, array $options = []): array
    {
        $query['custompost-types'] = ['post'];
        return $this->getCustomPosts($query, $options);
    }
    public function getPostCount(array $query = [], array $options = []): int
    {
        $query['custompost-types'] = ['post'];
        return $this->getCustomPostCount($query, $options);
    }
    public function getPostCustomPostType(): string
    {
        return 'post';
    }
}
