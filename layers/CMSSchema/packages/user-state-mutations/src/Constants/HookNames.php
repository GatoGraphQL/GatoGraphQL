<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\Constants;

class HookNames
{
    public const USER_LOGGED_IN = __CLASS__ . ':user:logged-in';
    public const USER_LOGGED_OUT = __CLASS__ . ':user:logged-out';
}
