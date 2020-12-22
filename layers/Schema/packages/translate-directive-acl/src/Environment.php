<?php

declare(strict_types=1);

namespace PoPSchema\TranslateDirectiveACL;

class Environment
{
    public const USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE = 'USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE';
    public const ANY_ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE = 'ANY_ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE';
    public const ANY_CAPABILITY_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE = 'ANY_CAPABILITY_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE';

    public static function userMustBeLoggedInToAccessTranslateDirective(): bool
    {
        return getenv(self::USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE) !== false ? strtolower(getenv(self::USER_MUST_BE_LOGGED_IN_TO_ACCESS_TRANSLATE_DIRECTIVE)) == "true" : false;
    }

    public static function anyRoleLoggedInUserMustHaveToAccessTranslateDirective(): array
    {
        return getenv(self::ANY_ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE) !== false ? json_decode(getenv(self::ANY_ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE)) : [];
    }

    public static function anyCapabilityLoggedInUserMustHaveToAccessTranslateDirective(): array
    {
        return getenv(self::ANY_CAPABILITY_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE) !== false ? json_decode(getenv(self::ANY_CAPABILITY_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_TRANSLATE_DIRECTIVE)) : [];
    }
}
