<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

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
        if (!ComponentConfiguration::enableAdminSchema()) {
            $excludedFieldNames = $objectTypeOrInterfaceTypeFieldResolver->getAdminFieldNames();
        }
        // 2. By filter hook
        $excludedFieldNames = $this->hooksAPI->applyFilters(
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
        $interfaceTypeResolverClasses = $objectTypeOrInterfaceTypeFieldResolver->getPartiallyImplementedInterfaceTypeResolverClasses();
        $fieldNames = array_filter(
            $fieldNames,
            fn ($fieldName) => $this->isFieldNameResolvedByFieldResolver(
                $objectTypeOrInterfaceTypeResolver,
                $objectTypeOrInterfaceTypeFieldResolver,
                $fieldName,
                $interfaceTypeResolverClasses,
            )
        );
        return $fieldNames;
    }

    /**
     * $interfaceTypeResolverClasses is the list of all the interfaces implemented
     * by the objectTypeOrInterfaceTypeFieldResolver, and not only those ones containing the fieldName.
     * This is because otherwise we'd need to call `$interfaceTypeResolver->getFieldNamesToImplement()`
     * to find out the list of Interfaces containing $fieldName, however this function relies
     * on the InterfaceTypeFieldResolver once again, so we'd get a recursion.
     */
    protected function isFieldNameResolvedByFieldResolver(
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName,
        array $interfaceTypeResolverClasses
    ): bool {
        // Execute 2 filters: a generic one, and a specific one
        if (
            $this->hooksAPI->applyFilters(
                HookHelpers::getHookNameToFilterField(),
                true,
                $objectTypeOrInterfaceTypeResolver,
                $objectTypeOrInterfaceTypeFieldResolver,
                $interfaceTypeResolverClasses,
                $fieldName
            )
        ) {
            return $this->hooksAPI->applyFilters(
                HookHelpers::getHookNameToFilterField($fieldName),
                true,
                $objectTypeOrInterfaceTypeResolver,
                $objectTypeOrInterfaceTypeFieldResolver,
                $interfaceTypeResolverClasses,
                $fieldName
            );
        }
        return false;
    }
}
