<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMedia\Misc;

use PoP\Root\App;
use PoPCMSSchema\CustomPostMedia\Facades\CustomPostMediaTypeAPIFacade;

class MediaHelpers
{
    public static function getThumbId($post_id)
    {
        $customPostMediaTypeAPI = CustomPostMediaTypeAPIFacade::getInstance();
        if ($thumb_id = $customPostMediaTypeAPI->getCustomPostThumbnailID($post_id)) {
            return $thumb_id;
        }

        // Default
        return App::applyFilters('getThumbId:default', null, $post_id);
    }
}
