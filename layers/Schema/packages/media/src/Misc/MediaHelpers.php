<?php

declare(strict_types=1);

namespace PoPSchema\Media\Misc;

use PoPSchema\Media\Facades\MediaTypeAPIFacade;

class MediaHelpers
{
    public static function getAttachmentImageProperties($imageid, $size = null)
    {
        $mediaTypeAPI = MediaTypeAPIFacade::getInstance();
        $img = $mediaTypeAPI->getImageAttributes($imageid, $size);
        return array(
            'src' => $img[0],
            'width' => $img[1],
            'height' => $img[2]
        );
    }
}
