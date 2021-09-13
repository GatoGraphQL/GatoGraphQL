<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

class HookHelpers
{
    public const HOOK_ENABLED_FIELD_NAMES = __CLASS__ . ':enabled_field_names';
    public const HOOK_ENABLED_DIRECTIVE_NAMES = __CLASS__ . ':resolved_directives_names';
    public const HOOK_SCHEMA_DEFINITION_FOR_FIELD = __CLASS__ . ':schema_definition_for_field:%s:%s';

    public static function getHookNameToFilterDirective(?string $directiveName = null): string
    {
        return self::HOOK_ENABLED_DIRECTIVE_NAMES . ($directiveName ? ':' . $directiveName : '');
    }

    public static function getHookNameToFilterField(?string $fieldName = null): string
    {
        return self::HOOK_ENABLED_FIELD_NAMES . ($fieldName ? ':' . $fieldName : '');
    }

    public static function getSchemaDefinitionForFieldHookName(string $typeOrFieldInterfaceResolverClass, string $fieldName): string
    {
        return sprintf(
            self::HOOK_SCHEMA_DEFINITION_FOR_FIELD,
            $typeOrFieldInterfaceResolverClass,
            $fieldName
        );
    }
}
