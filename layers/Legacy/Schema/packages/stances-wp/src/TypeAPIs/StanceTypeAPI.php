<?php

declare(strict_types=1);

namespace PoPSchema\StancesWP\TypeAPIs;

use PoPSchema\Stances\TypeAPIs\StanceTypeAPIInterface;
use WP_Post;

use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class StanceTypeAPI implements StanceTypeAPIInterface
{
    /**
     * Return the post's ID
     */
    public function getID(object $post): string | int
    {
        return $post->ID;
    }

    /**
     * Indicates if the passed object is of type Stance
     */
    public function isInstanceOfStanceType(object $object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type === \POP_USERSTANCE_POSTTYPE_USERSTANCE;
    }

    /**
     * Get the stance with provided ID or, if it doesn't exist, null
     */
    public function getStance(int | string $id): ?object
    {
        $post = get_post($id);
        if (!$post || $post->post_type !== \POP_USERSTANCE_POSTTYPE_USERSTANCE) {
            return null;
        }
        return $post;
    }

    /**
     * Indicate if an stance with provided ID exists
     */
    public function stanceExists(int | string $id): bool
    {
        return $this->getStance($id) != null;
    }
}
