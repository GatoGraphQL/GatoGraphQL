<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeAPIs;

use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface PostTypeAPIInterface extends CustomPostTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Post
     */
    public function isInstanceOfPostType(object $object): bool;
    /**
     * Indicate if an post with provided ID exists
     */
    public function postExists(int | string $id): bool;
    /**
     * Get the post with provided ID or, if it doesn't exist, null
     */
    public function getPost(int | string $id): ?object;
    /**
     * Get the list of posts.
     * If param "status" in $query is not passed, it defaults to "publish"
     */
    public function getPosts(array $query, array $options = []): array;
    /**
     * Get the number of posts.
     * If param "status" in $query is not passed, it defaults to "publish"
     */
    public function getPostCount(array $query = [], array $options = []): int;
    /**
     * Post custom post type
     */
    public function getPostCustomPostType(): string;
}
