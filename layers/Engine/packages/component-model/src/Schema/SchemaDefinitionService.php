<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class SchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    /**
     * Can't use autowiring or it produces a circular reference exception
     */
    protected ?AnyScalarScalarTypeResolver $anyScalarScalarTypeResolver = null;

    public function __construct(
        protected InstanceManagerInterface $instanceManager,
    ) {
    }

    public function getTypeSchemaKey(TypeResolverInterface $typeResolver): string
    {
        // By default, use the type name
        return $typeResolver->getMaybeNamespacedTypeName();
    }
    /**
     * The `AnyScalar` type is a wildcard type,
     * representing *any* type (string, int, bool, etc)
     */
    public function getDefaultType(): string
    {
        return SchemaDefinition::TYPE_ANY_SCALAR;
    }
    /**
     * The `AnyScalar` type is a wildcard type,
     * representing *any* type (string, int, bool, etc)
     */
    public function getDefaultTypeResolver(): ConcreteTypeResolverInterface
    {
        if ($this->anyScalarScalarTypeResolver === null) {
            $this->anyScalarScalarTypeResolver = $this->instanceManager->getInstance(AnyScalarScalarTypeResolver::class);
        }
        return $this->anyScalarScalarTypeResolver;
    }
}
