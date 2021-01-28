<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMedia\Misc;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPostMedia\Environment;

class MediaHelpers
{
    public static function getThumbId($post_id)
    {
        $cmsmediapostsapi = \PoPSchema\Media\PostsFunctionAPIFactory::getInstance();
        if ($thumb_id = $cmsmediapostsapi->getCustomPostThumbnailID($post_id)) {
            return $thumb_id;
        }

        // Default
        return HooksAPIFacade::getInstance()->applyFilters('getThumbId:default', Environment::getDefaultFeaturedImageID(), $post_id);
    }
}
