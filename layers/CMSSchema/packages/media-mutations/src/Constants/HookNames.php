<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\Constants;

class HookNames
{
    public final const CREATE_OR_UPDATE_MEDIA_ITEM = __CLASS__ . ':createOrUpdateMediaItem';
    public final const CREATE_MEDIA_ITEM = __CLASS__ . ':createMediaItem';
    public final const UPDATE_MEDIA_ITEM = __CLASS__ . ':updateMediaItem';

    public final const VALIDATE_CREATE_OR_UPDATE_MEDIA_ITEM = __CLASS__ . ':validateCreateOrUpdateMediaItem';
    public final const VALIDATE_CREATE_MEDIA_ITEM = __CLASS__ . ':validateCreateMediaItem';
    public final const VALIDATE_UPDATE_MEDIA_ITEM = __CLASS__ . ':validateUpdateMediaItem';

    public final const GET_CREATE_OR_UPDATE_MEDIA_ITEM_DATA = __CLASS__ . ':getCreateOrUpdateMediaItemData';
    public final const GET_CREATE_MEDIA_ITEM_DATA = __CLASS__ . ':getCreateMediaItemData';
    public final const GET_UPDATE_MEDIA_ITEM_DATA = __CLASS__ . ':getUpdateMediaItemData';

    public final const CREATE_OR_UPDATE_MEDIA_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS = __CLASS__ . ':createOrUpdateMediaItemInputFieldNameTypeResolvers';
    public final const CREATE_MEDIA_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS = __CLASS__ . ':createMediaItemInputFieldNameTypeResolvers';
    public final const UPDATE_MEDIA_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS = __CLASS__ . ':updateMediaItemInputFieldNameTypeResolvers';

    public final const CREATE_OR_UPDATE_MEDIA_ITEM_INPUT_FIELD_DESCRIPTION = __CLASS__ . ':createOrUpdateMediaItemInputFieldDescription';
    public final const CREATE_MEDIA_ITEM_INPUT_FIELD_DESCRIPTION = __CLASS__ . ':createMediaItemInputFieldDescription';
    public final const UPDATE_MEDIA_ITEM_INPUT_FIELD_DESCRIPTION = __CLASS__ . ':updateMediaItemInputFieldDescription';

    public final const CREATE_OR_UPDATE_MEDIA_ITEM_INPUT_FIELD_TYPE_MODIFIERS = __CLASS__ . ':createOrUpdateMediaItemInputFieldTypeModifiers';
    public final const CREATE_MEDIA_ITEM_INPUT_FIELD_TYPE_MODIFIERS = __CLASS__ . ':createMediaItemInputFieldTypeModifiers';
    public final const UPDATE_MEDIA_ITEM_INPUT_FIELD_TYPE_MODIFIERS = __CLASS__ . ':updateMediaItemInputFieldTypeModifiers';

    public final const ERROR_PAYLOAD = __CLASS__ . ':errorPayload';
}
