<?php

declare(strict_types=1);

namespace PoPSchema\HighlightsWP\TypeAPIs;

use function get_post;
use WP_Post;
use PoPSchema\Highlights\TypeAPIs\HighlightTypeAPIInterface;
/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class HighlightTypeAPI implements HighlightTypeAPIInterface
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
     * Indicates if the passed object is of type Highlight
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfHighlightType($object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type == \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT;
    }

    /**
     * Get the highlight with provided ID or, if it doesn't exist, null
     *
     * @param [type] $id
     * @return void
     */
    public function getHighlight($id)
    {
        $post = get_post($id);
        if (!$post || $post->post_type != \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT) {
            return null;
        }
        return $post;
    }

    /**
     * Indicate if an highlight with provided ID exists
     *
     * @param [type] $id
     * @return void
     */
    public function highlightExists($id): bool
    {
        return $this->getHighlight($id) != null;
    }
}
