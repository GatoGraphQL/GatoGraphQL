<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMedia;

class Environment
{
    public static function getDefaultFeaturedImageID(): ?int
    {
        return getenv('DEFAULT_FEATURED_IMAGE_ID') !== false ? (int)getenv('DEFAULT_FEATURED_IMAGE_ID') : null;
    }
}
