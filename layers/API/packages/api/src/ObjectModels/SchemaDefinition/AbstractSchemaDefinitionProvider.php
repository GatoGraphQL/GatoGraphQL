<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
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

    protected function replaceTypeResolverWithTypeType(array &$schemaDefinition): void
    {
        $typeType = null;
        $typeResolver = $schemaDefinition[SchemaDefinition::TYPE_RESOLVER];
        if ($typeResolver instanceof ObjectTypeResolverInterface) {
            $typeType = SchemaDefinition::TYPE_OBJECT;
        } elseif ($typeResolver instanceof InterfaceTypeResolverInterface) {
            $typeType = SchemaDefinition::TYPE_INTERFACE;
        } elseif ($typeResolver instanceof UnionTypeResolverInterface) {
            $typeType = SchemaDefinition::TYPE_UNION;
        } elseif ($typeResolver instanceof ScalarTypeResolverInterface) {
            $typeType = SchemaDefinition::TYPE_SCALAR;
        } elseif ($typeResolver instanceof EnumTypeResolverInterface) {
            $typeType = SchemaDefinition::TYPE_ENUM;
        } elseif ($typeResolver instanceof InputObjectTypeResolverInterface) {
            $typeType = SchemaDefinition::TYPE_INPUT_OBJECT;
        }
        $schemaDefinition[SchemaDefinition::TYPE_TYPE] = $typeType;
        unset($schemaDefinition[SchemaDefinition::TYPE_RESOLVER]);
    }
}
