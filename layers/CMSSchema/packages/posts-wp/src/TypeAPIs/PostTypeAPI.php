<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostsWP\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use WP_Post;

use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTypeAPI extends AbstractCustomPostTypeAPI implements PostTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * Add an extra hook just to modify posts
     *
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     */
    protected function convertCustomPostsQuery(array $query, array $options = []): array
    {
        return App::applyFilters(
            self::HOOK_QUERY,
            parent::convertCustomPostsQuery($query, $options),
            $options
        );
    }

    /**
     * Query args that must always be in the query
     *
     * @return array<string,mixed>
     */
    public function getCustomPostQueryRequiredArgs(): array
    {
        return array_merge(
            parent::getCustomPostQueryRequiredArgs(),
            [
                'custompost-types' => ['post'],
            ]
        );
    }

    /**
     * Indicates if the passed object is of type Post
     */
    public function isInstanceOfPostType(object $object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type === 'post';
    }

    /**
     * Get the post with provided ID or, if it doesn't exist, null
     */
    public function getPost(int|string $id): ?object
    {
        $post = get_post((int)$id);
        if ($post === null || $post->post_type !== 'post') {
            return null;
        }
        return $post;
    }

    /**
     * Indicate if an post with provided ID exists
     */
    public function postExists(int|string $id): bool
    {
        return $this->getPost($id) !== null;
    }

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getPosts(array $query, array $options = []): array
    {
        return $this->getCustomPosts($query, $options);
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getPostCount(array $query, array $options = []): int
    {
        return $this->getCustomPostCount($query, $options);
    }
    public function getPostCustomPostType(): string
    {
        return 'post';
    }
}
