<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\Constants;

class PostCategoryCRUDHookNames
{
    public final const VALIDATE_CREATE_OR_UPDATE = __CLASS__ . ':validate-create-or-update';
    public final const VALIDATE_CREATE = __CLASS__ . ':validate-create';
    public final const VALIDATE_UPDATE = __CLASS__ . ':validate-update';
    public final const EXECUTE_CREATE_OR_UPDATE = __CLASS__ . ':execute-create-or-update';
    public final const EXECUTE_CREATE = __CLASS__ . ':execute-create';
    public final const EXECUTE_UPDATE = __CLASS__ . ':execute-update';
    public final const GET_CREATE_OR_UPDATE_DATA = __CLASS__ . ':get-create-or-update-data';
    public final const GET_CREATE_DATA = __CLASS__ . ':get-create-data';
    public final const GET_UPDATE_DATA = __CLASS__ . ':get-update-data';
    // public final const ERROR_PAYLOAD = __CLASS__ . ':error-payload';
}
