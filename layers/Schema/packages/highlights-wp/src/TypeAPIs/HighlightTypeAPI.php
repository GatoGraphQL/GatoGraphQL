<?php

declare(strict_types=1);

namespace PoPSchema\HighlightsWP\TypeAPIs;

use WP_Post;
use PoPSchema\Highlights\TypeAPIs\HighlightTypeAPIInterface;

use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class HighlightTypeAPI implements HighlightTypeAPIInterface
{
    /**
     * Return the post's ID
     */
    public function getID(object $post): mixed
    {
        return $post->ID;
    }

    /**
     * Indicates if the passed object is of type Highlight
     */
    public function isInstanceOfHighlightType(object $object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type == \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT;
    }

    /**
     * Get the highlight with provided ID or, if it doesn't exist, null
     */
    public function getHighlight(mixed $id): ?object
    {
        $post = get_post($id);
        if (!$post || $post->post_type != \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT) {
            return null;
        }
        return $post;
    }

    /**
     * Indicate if an highlight with provided ID exists
     */
    public function highlightExists(mixed $id): bool
    {
        return $this->getHighlight($id) != null;
    }
}
