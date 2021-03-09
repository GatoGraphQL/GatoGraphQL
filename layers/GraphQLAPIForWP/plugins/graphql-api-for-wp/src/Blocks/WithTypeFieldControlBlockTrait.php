<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Constants\BlockConstants;
use PoP\ComponentModel\Facades\Registries\TypeRegistryFacade;
use PoP\ComponentModel\Facades\Registries\FieldInterfaceRegistryFacade;

trait WithTypeFieldControlBlockTrait
{
    /**
     * Convert the typeFields from the format saved in the post: "typeNamespacedName.fieldName",
     * to the one suitable for printing on the page, to show the user: "typeName/fieldName"
     *
     * @param string[] $typeFields
     * @return string[]|array<string,array>
     */
    public function getTypeFieldsForPrint(array $typeFields): array
    {
        $groupFieldsUnderTypeForPrint = ComponentConfiguration::groupFieldsUnderTypeForPrint();
        $typeRegistry = TypeRegistryFacade::getInstance();
        $fieldInterfaceRegistry = FieldInterfaceRegistryFacade::getInstance();
        // For each class, obtain its namespacedTypeName
        $typeResolvers = $typeRegistry->getTypeResolvers();
        $namespacedTypeNameNames = [];
        foreach ($typeResolvers as $typeResolver) {
            $typeResolverNamespacedName = $typeResolver->getNamespacedTypeName();
            $namespacedTypeNameNames[$typeResolverNamespacedName] = $typeResolver->getMaybeNamespacedTypeName();
        }
        // For each interface, obtain its namespacedInterfaceName
        $fieldInterfaceResolvers = $fieldInterfaceRegistry->getFieldInterfaceResolvers();
        $namespacedFieldInterfaceNameClasses = [];
        foreach ($fieldInterfaceResolvers as $fieldInterfaceResolver) {
            $fieldInterfaceResolverNamespacedName = $fieldInterfaceResolver->getNamespacedInterfaceName();
            $namespacedFieldInterfaceNameClasses[$fieldInterfaceResolverNamespacedName] = $fieldInterfaceResolver->getMaybeNamespacedInterfaceName();
        }
        $typeFieldsForPrint = [];
        foreach ($typeFields as $selectedField) {
            // The field is composed by the type namespaced name, and the field name, separated by "."
            // Extract these values
            $entry = explode(BlockConstants::TYPE_FIELD_SEPARATOR_FOR_DB, $selectedField);
            $namespacedTypeOrInterfaceName = $entry[0];
            $field = $entry[1];
            // It can either be a type, or an interface. If not, return the same element
            $typeOrInterfaceName =
                $namespacedTypeNameNames[$namespacedTypeOrInterfaceName]
                ?? $namespacedFieldInterfaceNameClasses[$namespacedTypeOrInterfaceName]
                ?? $namespacedTypeOrInterfaceName;
            /**
             * If $groupFieldsUnderTypeForPrint is true, combine all types under their shared typeName
             * If $groupFieldsUnderTypeForPrint is false, replace namespacedTypeName for typeName and "." for "/"
             * */
            if ($groupFieldsUnderTypeForPrint) {
                $typeFieldsForPrint[$typeOrInterfaceName][] = $field;
            } else {
                $typeFieldsForPrint[] = $typeOrInterfaceName . BlockConstants::TYPE_FIELD_SEPARATOR_FOR_PRINT . $field;
            }
        }
        return $typeFieldsForPrint;
    }
}
