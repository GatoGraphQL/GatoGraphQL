<?php

declare(strict_types=1);

namespace PoP\Engine\Schema;

use PoP\Root\Services\AbstractBasicService;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;

class SchemaDefinitionService extends AbstractBasicService implements SchemaDefinitionServiceInterface
{
    private ?RootObjectTypeResolver $rootObjectTypeResolver = null;
    private ?AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver = null;

    final protected function getRootObjectTypeResolver(): RootObjectTypeResolver
    {
        if ($this->rootObjectTypeResolver === null) {
            /** @var RootObjectTypeResolver */
            $rootObjectTypeResolver = $this->instanceManager->getInstance(RootObjectTypeResolver::class);
            $this->rootObjectTypeResolver = $rootObjectTypeResolver;
        }
        return $this->rootObjectTypeResolver;
    }
    final protected function getAnyBuiltInScalarScalarTypeResolver(): AnyBuiltInScalarScalarTypeResolver
    {
        if ($this->anyBuiltInScalarScalarTypeResolver === null) {
            /** @var AnyBuiltInScalarScalarTypeResolver */
            $anyBuiltInScalarScalarTypeResolver = $this->instanceManager->getInstance(AnyBuiltInScalarScalarTypeResolver::class);
            $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
        }
        return $this->anyBuiltInScalarScalarTypeResolver;
    }

    /**
     * The `AnyBuiltInScalar` type is a wildcard type,
     * representing any of GraphQL's built-in types
     * (String, Int, Boolean, Float or ID)
     */
    public function getDefaultConcreteTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->getAnyBuiltInScalarScalarTypeResolver();
    }
    /**
     * The `AnyBuiltInScalar` type is a wildcard type,
     * representing any of GraphQL's built-in types
     * (String, Int, Boolean, Float or ID)
     */
    public function getDefaultInputTypeResolver(): InputTypeResolverInterface
    {
        return $this->getAnyBuiltInScalarScalarTypeResolver();
    }

    public function getSchemaRootObjectTypeResolver(): RootObjectTypeResolver
    {
        return $this->getRootObjectTypeResolver();
    }
}
