<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\Constants;

class HookNames
{
    public final const CREATE_MEDIA_ITEM = __CLASS__ . ':createMediaItem';
    public final const VALIDATE_CREATE_MEDIA_ITEM = __CLASS__ . ':validateCreateMediaItem';
    public final const GET_CREATE_MEDIA_ITEM_DATA = __CLASS__ . ':getCreateMediaItemData';
}
