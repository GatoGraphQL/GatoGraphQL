<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\Constants;

class CategoryMetaCRUDHookNames
{
    public final const VALIDATE_SET_META = __CLASS__ . ':validate-set-meta';
    public final const VALIDATE_ADD_META = __CLASS__ . ':validate-add-meta';
    public final const VALIDATE_UPDATE_META = __CLASS__ . ':validate-update-meta';
    public final const VALIDATE_DELETE_META = __CLASS__ . ':validate-delete-meta';
    public final const EXECUTE_SET_META = __CLASS__ . ':execute-set-meta';
    public final const EXECUTE_ADD_META = __CLASS__ . ':execute-add-meta';
    public final const EXECUTE_UPDATE_META = __CLASS__ . ':execute-update-meta';
    public final const EXECUTE_DELETE_META = __CLASS__ . ':execute-delete-meta';
    public final const GET_SET_META_DATA = __CLASS__ . ':get-set-meta-data';
    public final const GET_ADD_META_DATA = __CLASS__ . ':get-add-meta-data';
    public final const GET_UPDATE_META_DATA = __CLASS__ . ':get-update-meta-data';
    public final const GET_DELETE_META_DATA = __CLASS__ . ':get-delete-meta-data';
    public final const ERROR_PAYLOAD = __CLASS__ . ':error-payload';
}
