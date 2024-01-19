<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\Constants;

class HookNames
{
    public final const CREATE_MEDIA_ITEM = __CLASS__ . ':createMediaItem';
    public final const VALIDATE_CREATE_MEDIA_ITEM = __CLASS__ . ':validateCreateMediaItem';
    public final const GET_CREATE_MEDIA_ITEM_DATA = __CLASS__ . ':getCreateMediaItemData';
    public final const CREATE_MEDIA_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS = __CLASS__ . ':createMediaItemInputFieldNameTypeResolvers';
    public final const CREATE_MEDIA_ITEM_INPUT_FIELD_DESCRIPTION = __CLASS__ . ':createMediaItemInputFieldDescription';
    public final const CREATE_MEDIA_ITEM_INPUT_FIELD_TYPE_MODIFIERS = __CLASS__ . ':createMediaItemInputFieldTypeModifiers';
    public final const ERROR_PAYLOAD = __CLASS__ . ':errorPayload';
}
