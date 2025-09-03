<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

interface ApplicationPasswordUsersInterface
{
    public const USER_ADMIN = 'admin';
    public const USER_EDITOR = 'editor';
    public const USER_AUTHOR = 'author';
    public const USER_CONTRIBUTOR = 'contributor';
    public const USER_SUBSCRIBER = 'subscriber';
}
