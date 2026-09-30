<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\Enums;

enum MenuItemType: string
{
    case CUSTOM = 'custom';
    case POST_TYPE = 'post_type';
    case TAXONOMY = 'taxonomy';
}
