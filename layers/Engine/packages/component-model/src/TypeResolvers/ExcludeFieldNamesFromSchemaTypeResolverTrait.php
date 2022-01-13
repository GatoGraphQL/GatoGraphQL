<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\Root\App;
use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

trait ExcludeFieldNamesFromSchemaTypeResolverTrait
{
    /**
     * Call a hook to allow removing fields from the schema
     *
     * @return string[]
     */
    protected function maybeExcludeFieldNamesFromSchema(
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        array $fieldNames
    ): array {
        // Enable to exclude fieldNames, so they are not added to the schema.
        $excludedFieldNames = [];
        // Whenever:
        // 1. Exclude the admin fields, if "Admin" Schema is not enabled
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (!$componentConfiguration->enableAdminSchema()) {
            $excludedFieldNames = $objectTypeOrInterfaceTypeFieldResolver->getAdminFieldNames();
        }
        // 2. By filter hook
        $excludedFieldNames = \PoP\Root\App::getHookManager()->applyFilters(
            Hooks::EXCLUDE_FIELDNAMES,
            $excludedFieldNames,
            $objectTypeOrInterfaceTypeFieldResolver,
            $fieldNames
        );
        if ($excludedFieldNames !== []) {
            $fieldNames = array_values(array_diff(
                $fieldNames,
                $excludedFieldNames
            ));
        }

        // Execute a hook, allowing to filter them out (eg: removing fieldNames from a private schema)
        // Also pass the Interfaces defining the field
        $fieldNames = array_filter(
            $fieldNames,
            fn ($fieldName) => $this->isFieldNameResolvedByObjectTypeFieldResolver(
                $objectTypeOrInterfaceTypeResolver,
                $objectTypeOrInterfaceTypeFieldResolver,
                $fieldName,
            )
        );
        return $fieldNames;
    }

    protected function isFieldNameResolvedByObjectTypeFieldResolver(
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName
    ): bool {
        // Execute 2 filters: a generic one, and a specific one
        if (
            \PoP\Root\App::getHookManager()->applyFilters(
                HookHelpers::getHookNameToFilterField(),
                true,
                $objectTypeOrInterfaceTypeResolver,
                $objectTypeOrInterfaceTypeFieldResolver,
                $fieldName
            )
        ) {
            return \PoP\Root\App::getHookManager()->applyFilters(
                HookHelpers::getHookNameToFilterField($fieldName),
                true,
                $objectTypeOrInterfaceTypeResolver,
                $objectTypeOrInterfaceTypeFieldResolver,
                $fieldName
            );
        }
        return false;
    }
}
