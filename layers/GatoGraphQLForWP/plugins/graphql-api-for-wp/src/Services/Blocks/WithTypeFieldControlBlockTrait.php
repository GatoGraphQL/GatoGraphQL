<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Constants\BlockConstants;
use PoP\ComponentModel\Registries\TypeRegistryInterface;

trait WithTypeFieldControlBlockTrait
{
    abstract protected function getTypeRegistry(): TypeRegistryInterface;

    /**
     * Convert the typeFields from the format saved in the post: "typeNamespacedName.fieldName",
     * to the one suitable for printing on the page, to show the user: "typeName/fieldName"
     *
     * @param string[] $typeFields
     * @return string[]|array<string,string[]>
     */
    public function getTypeFieldsForPrint(array $typeFields): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $groupFieldsUnderTypeForPrint = $moduleConfiguration->groupFieldsUnderTypeForPrint();
        // For each class, obtain its namespacedTypeName
        $objectTypeResolvers = $this->getTypeRegistry()->getObjectTypeResolvers();
        /** @var array<string,string> */
        $namespacedObjectTypeNameNames = [];
        foreach ($objectTypeResolvers as $objectTypeResolver) {
            $objectTypeResolverNamespacedName = $objectTypeResolver->getNamespacedTypeName();
            $namespacedObjectTypeNameNames[$objectTypeResolverNamespacedName] = $objectTypeResolver->getMaybeNamespacedTypeName();
        }
        // For each interface, obtain its namespacedInterfaceName
        $interfaceTypeResolvers = $this->getTypeRegistry()->getInterfaceTypeResolvers();
        /** @var array<string,string> */
        $namespacedInterfaceTypeNameNames = [];
        foreach ($interfaceTypeResolvers as $interfaceTypeResolver) {
            $interfaceTypeResolverNamespacedName = $interfaceTypeResolver->getNamespacedTypeName();
            $namespacedInterfaceTypeNameNames[$interfaceTypeResolverNamespacedName] = $interfaceTypeResolver->getMaybeNamespacedTypeName();
        }
        /** @var string[]|array<string,string[]> */
        $typeFieldsForPrint = [];
        foreach ($typeFields as $selectedField) {
            // The field is composed by the type namespaced name, and the field name, separated by "."
            // Extract these values
            $entry = explode(BlockConstants::TYPE_FIELD_SEPARATOR_FOR_DB, $selectedField);
            /** @var string */
            $namespacedObjectTypeOrInterfaceTypeName = $entry[0];
            /** @var string */
            $field = $entry[1];
            // It can either be a type, or an interface. If not, return the same element
            $objectTypeOrInterfaceTypeName =
                $namespacedObjectTypeNameNames[$namespacedObjectTypeOrInterfaceTypeName]
                ?? $namespacedInterfaceTypeNameNames[$namespacedObjectTypeOrInterfaceTypeName]
                ?? $namespacedObjectTypeOrInterfaceTypeName;
            /**
             * If $groupFieldsUnderTypeForPrint is true, combine all types under their shared typeName
             * If $groupFieldsUnderTypeForPrint is false, replace namespacedTypeName for typeName and "." for "/"
             * */
            if ($groupFieldsUnderTypeForPrint) {
                /** @var array<string,string[]> $typeFieldsForPrint */
                $typeFieldsForPrint[$objectTypeOrInterfaceTypeName][] = $field;
            } else {
                /** @var string[] $typeFieldsForPrint */
                $typeFieldsForPrint[] = $objectTypeOrInterfaceTypeName . BlockConstants::TYPE_FIELD_SEPARATOR_FOR_PRINT . $field;
            }
        }
        return $typeFieldsForPrint;
    }
}
