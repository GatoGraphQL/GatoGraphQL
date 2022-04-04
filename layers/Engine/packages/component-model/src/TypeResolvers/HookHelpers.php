<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

class HookHelpers
{
    public final const HOOK_ENABLED_FIELD_NAMES = __CLASS__ . ':enabled_field_names';
    public final const HOOK_ENABLED_DIRECTIVE_NAMES = __CLASS__ . ':resolved_directives_names';

    public static function getHookNameToFilterDirective(?string $directiveName = null): string
    {
        return self::HOOK_ENABLED_DIRECTIVE_NAMES . ($directiveName ? ':' . $directiveName : '');
    }

    public static function getHookNameToFilterField(?string $fieldName = null): string
    {
        return self::HOOK_ENABLED_FIELD_NAMES . ($fieldName ? ':' . $fieldName : '');
    }
}
