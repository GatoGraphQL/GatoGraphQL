<?php
namespace PoPSchema\Media\WP;

class PostsFunctionAPI extends \PoPSchema\Media\PostsFunctionAPI_Base
{
    public function hasCustomPostThumbnail($post_id)
    {
        return has_post_thumbnail($post_id);
    }
    public function getCustomPostThumbnailID($post_id)
    {
        if ($id = get_post_thumbnail_id($post_id)) {
            return (int)$id;
        }
        return null;
    }
}

/**
 * Initialize
 */
new PostsFunctionAPI();
