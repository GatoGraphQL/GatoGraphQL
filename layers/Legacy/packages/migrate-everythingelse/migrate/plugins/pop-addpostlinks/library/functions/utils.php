<?php

class PoP_AddPostLinks_Utils
{
    public static function getLink($post_id)
    {
        return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($post_id, GD_METAKEY_POST_LINK, true);
    }
}
