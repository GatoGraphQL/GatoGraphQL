<?php

declare(strict_types=1);

namespace PoPSchema\StancesWP\TypeAPIs;

use WP_Post;
use PoPSchema\Stances\TypeAPIs\StanceTypeAPIInterface;

use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class StanceTypeAPI implements StanceTypeAPIInterface
{
    /**
     * Return the post's ID
     *
     * @param [type] $post
     * @return void
     */
    public function getID($post)
    {
        return $post->ID;
    }

    /**
     * Indicates if the passed object is of type Stance
     *
     * @param [type] $object
     */
    public function isInstanceOfStanceType($object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type == \POP_USERSTANCE_POSTTYPE_USERSTANCE;
    }

    /**
     * Get the stance with provided ID or, if it doesn't exist, null
     *
     * @param [type] $id
     * @return void
     */
    public function getStance($id)
    {
        $post = get_post($id);
        if (!$post || $post->post_type != \POP_USERSTANCE_POSTTYPE_USERSTANCE) {
            return null;
        }
        return $post;
    }

    /**
     * Indicate if an stance with provided ID exists
     *
     * @param [type] $id
     * @return void
     */
    public function stanceExists($id): bool
    {
        return $this->getStance($id) != null;
    }
}
