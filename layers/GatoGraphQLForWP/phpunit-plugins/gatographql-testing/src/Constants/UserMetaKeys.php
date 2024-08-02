<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants;

class UserMetaKeys
{
    public const APP_PASSWORD = 'app_password';

    /**
     * UserRole specific meta keys
     */
    public const APP_PASSWORD_ADMIN = 'app_password:admin';
    public const APP_PASSWORD_EDITOR = 'app_password:editor';
    public const APP_PASSWORD_AUTHOR = 'app_password:author';
    public const APP_PASSWORD_CONTRIBUTOR = 'app_password:contributor';
    public const APP_PASSWORD_SUBSCRIBER = 'app_password:subscriber';
}
