<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

/**
 * Helpers for setting up hooks
 */
class HookHelpers
{
    public const HOOK_SCHEMA_DEFINITION_FOR_FIELD = __CLASS__ . ':schema_definition_for_field:%s:%s';

    public static function getSchemaDefinitionForFieldHookName(string $typeOrFieldInterfaceResolverClass, string $fieldName): string
    {
        return sprintf(
            self::HOOK_SCHEMA_DEFINITION_FOR_FIELD,
            $typeOrFieldInterfaceResolverClass,
            $fieldName
        );
    }
}
