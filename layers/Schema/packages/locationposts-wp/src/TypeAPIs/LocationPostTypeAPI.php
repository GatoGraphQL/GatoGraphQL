<?php

declare(strict_types=1);

namespace PoPSchema\LocationPostsWP\TypeAPIs;

use WP_Post;
use PoPSchema\PostsWP\TypeAPIs\PostTypeAPI;
use PoPSchema\LocationPosts\TypeAPIs\LocationPostTypeAPIInterface;

use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class LocationPostTypeAPI extends PostTypeAPI implements LocationPostTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type LocationPost
     */
    public function isInstanceOfLocationPostType(object $object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type == \POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST;
    }

    /**
     * Get the locationPost with provided ID or, if it doesn't exist, null
     */
    public function getLocationPost(int | string $id): ?object
    {
        $post = get_post($id);
        if (!$post || $post->post_type != \POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST) {
            return null;
        }
        return $post;
    }

    /**
     * Indicate if an locationPost with provided ID exists
     */
    public function locationPostExists(int | string $id): bool
    {
        return $this->getLocationPost($id) != null;
    }

    public function getLocationPosts(array $query, array $options = []): array
    {
        return $this->getPosts($query, $options);
    }
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    protected function convertCustomPostsQuery(array $query, array $options = []): array
    {
        $query = parent::convertCustomPostsQuery($query, $options);
        $query['post_type'] = array(\POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST);
        return $query;
    }
}
