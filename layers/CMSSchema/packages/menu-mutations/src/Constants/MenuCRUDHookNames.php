<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\Constants;

class MenuCRUDHookNames
{
    public final const CREATE_OR_UPDATE_MENU_ITEM = __CLASS__ . ':createOrUpdateMenu';
    public final const CREATE_MENU_ITEM = __CLASS__ . ':createMenu';
    public final const UPDATE_MENU_ITEM = __CLASS__ . ':updateMenu';

    public final const VALIDATE_CREATE_OR_UPDATE_MENU_ITEM = __CLASS__ . ':validateCreateOrUpdateMenu';
    public final const VALIDATE_CREATE_MENU_ITEM = __CLASS__ . ':validateCreateMenu';
    public final const VALIDATE_UPDATE_MENU_ITEM = __CLASS__ . ':validateUpdateMenu';

    public final const GET_CREATE_OR_UPDATE_MENU_ITEM_DATA = __CLASS__ . ':getCreateOrUpdateMenuData';
    public final const GET_CREATE_MENU_ITEM_DATA = __CLASS__ . ':getCreateMenuData';
    public final const GET_UPDATE_MENU_ITEM_DATA = __CLASS__ . ':getUpdateMenuData';

    public final const CREATE_OR_UPDATE_MENU_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS = __CLASS__ . ':createOrUpdateMenuInputFieldNameTypeResolvers';
    public final const CREATE_MENU_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS = __CLASS__ . ':createMenuInputFieldNameTypeResolvers';
    public final const UPDATE_MENU_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS = __CLASS__ . ':updateMenuInputFieldNameTypeResolvers';

    public final const CREATE_OR_UPDATE_MENU_ITEM_INPUT_FIELD_DESCRIPTION = __CLASS__ . ':createOrUpdateMenuInputFieldDescription';
    public final const CREATE_MENU_ITEM_INPUT_FIELD_DESCRIPTION = __CLASS__ . ':createMenuInputFieldDescription';
    public final const UPDATE_MENU_ITEM_INPUT_FIELD_DESCRIPTION = __CLASS__ . ':updateMenuInputFieldDescription';

    public final const CREATE_OR_UPDATE_MENU_ITEM_INPUT_FIELD_TYPE_MODIFIERS = __CLASS__ . ':createOrUpdateMenuInputFieldTypeModifiers';
    public final const CREATE_MENU_ITEM_INPUT_FIELD_TYPE_MODIFIERS = __CLASS__ . ':createMenuInputFieldTypeModifiers';
    public final const UPDATE_MENU_ITEM_INPUT_FIELD_TYPE_MODIFIERS = __CLASS__ . ':updateMenuInputFieldTypeModifiers';

    public final const ERROR_PAYLOAD = __CLASS__ . ':errorPayload';
}
