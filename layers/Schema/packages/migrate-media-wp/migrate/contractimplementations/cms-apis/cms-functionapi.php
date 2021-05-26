<?php

namespace PoPSchema\Media\WP;

class FunctionAPI extends \PoPSchema\Media\FunctionAPI_Base
{
    public function getMediaObject($media_id)
    {
        return get_post($media_id);
    }
    public function getMediaDescription($media_id)
    {
        $media = get_post($media_id);
        return $media->post_content;
    }
    public function getMediaMimeType($media_id)
    {
        return get_post_mime_type($media_id);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
