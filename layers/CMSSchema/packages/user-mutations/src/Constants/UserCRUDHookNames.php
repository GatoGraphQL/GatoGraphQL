<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\Constants;

class UserCRUDHookNames
{
    public final const CREATE_OR_UPDATE_USER = __CLASS__ . ':createOrUpdateUser';
    public final const CREATE_USER = __CLASS__ . ':createUser';
    public final const UPDATE_USER = __CLASS__ . ':updateUser';
    public final const DELETE_USER = __CLASS__ . ':deleteUser';

    public final const VALIDATE_CREATE_OR_UPDATE_USER = __CLASS__ . ':validateCreateOrUpdateUser';
    public final const VALIDATE_CREATE_USER = __CLASS__ . ':validateCreateUser';
    public final const VALIDATE_UPDATE_USER = __CLASS__ . ':validateUpdateUser';
    public final const VALIDATE_DELETE_USER = __CLASS__ . ':validateDeleteUser';

    public final const GET_CREATE_OR_UPDATE_USER_DATA = __CLASS__ . ':getCreateOrUpdateUserData';
    public final const GET_CREATE_USER_DATA = __CLASS__ . ':getCreateUserData';
    public final const GET_UPDATE_USER_DATA = __CLASS__ . ':getUpdateUserData';

    public final const ERROR_PAYLOAD = __CLASS__ . ':errorPayload';
}
