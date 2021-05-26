<?php

declare(strict_types=1);

namespace PoPSchema\Media\Misc;

use PoPSchema\Media\Facades\MediaTypeAPIFacade;

class MediaHelpers
{
    public static function getAttachmentImageProperties($imageid, $size = null)
    {
        $mediaTypeAPI = MediaTypeAPIFacade::getInstance();
        return $mediaTypeAPI->getImageProperties($imageid, $size);
    }
}
