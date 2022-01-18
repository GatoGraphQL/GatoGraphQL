<?php

declare(strict_types=1);

namespace PoPAPI\API\Schema;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class SchemaDefinitionHelpers
{
    /**
     * Replace the typeResolver with the typeName (maybe namespaced) and kind
     */
    public static function replaceTypeResolverWithTypeProperties(array &$schemaDefinition): void
    {
        $typeResolver = $schemaDefinition[SchemaDefinition::TYPE_RESOLVER];
        $schemaDefinition[SchemaDefinition::TYPE_NAME] = $typeResolver->getMaybeNamespacedTypeName();

        $typeKind = null;
        if ($typeResolver instanceof ObjectTypeResolverInterface) {
            $typeKind = TypeKinds::OBJECT;
        } elseif ($typeResolver instanceof InterfaceTypeResolverInterface) {
            $typeKind = TypeKinds::INTERFACE;
        } elseif ($typeResolver instanceof UnionTypeResolverInterface) {
            $typeKind = TypeKinds::UNION;
        } elseif ($typeResolver instanceof ScalarTypeResolverInterface) {
            $typeKind = TypeKinds::SCALAR;
        } elseif ($typeResolver instanceof EnumTypeResolverInterface) {
            $typeKind = TypeKinds::ENUM;
        } elseif ($typeResolver instanceof InputObjectTypeResolverInterface) {
            $typeKind = TypeKinds::INPUT_OBJECT;
        }
        $schemaDefinition[SchemaDefinition::TYPE_KIND] = $typeKind;

        unset($schemaDefinition[SchemaDefinition::TYPE_RESOLVER]);
    }
}
