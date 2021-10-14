<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\API\Schema\TypeKinds;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

abstract class AbstractSchemaDefinitionProvider implements SchemaDefinitionProviderInterface
{
    /**
     * @var array<string, TypeResolverInterface|DirectiveResolverInterface> Key: class, Value: Accessed Type and Directive Resolver
     */
    protected array $accessedTypeAndDirectiveResolvers = [];

    final public function getAccessedTypeAndDirectiveResolvers(): array
    {
        return array_values($this->accessedTypeAndDirectiveResolvers);
    }

    /**
     * Replace the typeResolver with the typeName (maybe namespaced) and kind
     */
    protected function replaceTypeResolverWithTypeProperties(array &$schemaDefinition): void
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
