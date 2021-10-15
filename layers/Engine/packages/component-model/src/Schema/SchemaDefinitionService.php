<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    protected AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver;
    protected InstanceManagerInterface $instanceManager;
    protected TranslationAPIInterface $translationAPI;

    #[Required]
    final public function autowireSchemaDefinitionService(
        InstanceManagerInterface $instanceManager,
        AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver,
        TranslationAPIInterface $translationAPI,
    ): void {
        $this->instanceManager = $instanceManager;
        $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
        $this->translationAPI = $translationAPI;
    }

    /**
     * The `AnyBuiltInScalar` type is a wildcard type,
     * representing any of GraphQL's built-in types
     * (String, Int, Boolean, Float or ID)
     */
    public function getDefaultConcreteTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->anyBuiltInScalarScalarTypeResolver;
    }
    /**
     * The `AnyBuiltInScalar` type is a wildcard type,
     * representing any of GraphQL's built-in types
     * (String, Int, Boolean, Float or ID)
     */
    public function getDefaultInputTypeResolver(): InputTypeResolverInterface
    {
        return $this->anyBuiltInScalarScalarTypeResolver;
    }
}
