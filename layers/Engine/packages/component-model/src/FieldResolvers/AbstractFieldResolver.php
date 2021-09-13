<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

abstract class AbstractFieldResolver implements FieldResolverInterface
{
    public function getAdminFieldNames(): array
    {
        return [];
    }

    /**
     * Apply hook to override the values, eg: by the Field Deprecation List
     */
    final protected function triggerHookToOverrideSchemaDefinition(
        array $schemaDefinition,
        TypeResolverInterface $typeResolver,
        string $fieldName,
        array $fieldArgs,
    ): array {
        $hookName = HookHelpers::getSchemaDefinitionForFieldHookName(
            get_class($typeResolver),
            $fieldName
        );
        return $this->hooksAPI->applyFilters(
            $hookName,
            $schemaDefinition,
            $typeResolver,
            $this,
            $fieldName,
            $fieldArgs
        );
    }
}
