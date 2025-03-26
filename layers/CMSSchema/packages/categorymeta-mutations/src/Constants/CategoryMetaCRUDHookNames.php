<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\Constants;

class CategoryMetaCRUDHookNames
{
    public final const VALIDATE_SET_META = __CLASS__ . ':validate-create-or-update';
    public final const VALIDATE_ADD_META = __CLASS__ . ':validate-create';
    public final const VALIDATE_UPDATE_META = __CLASS__ . ':validate-update';
    public final const EXECUTE_SET_META = __CLASS__ . ':execute-create-or-update';
    public final const EXECUTE_ADD_META = __CLASS__ . ':execute-create';
    public final const EXECUTE_UPDATE_META = __CLASS__ . ':execute-update';
    public final const GET_SET_META_DATA = __CLASS__ . ':get-create-or-update-data';
    public final const GET_ADD_META_DATA = __CLASS__ . ':get-create-data';
    public final const GET_UPDATE_META_DATA = __CLASS__ . ':get-update-data';
    public final const ERROR_PAYLOAD = __CLASS__ . ':error-payload';
}
