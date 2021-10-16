<?php

declare(strict_types=1);

namespace PoP\Engine\Schema;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionService as UpstreamSchemaDefinitionService;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    protected RootObjectTypeResolver $rootObjectTypeResolver;
    protected AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver;
    protected InstanceManagerInterface $instanceManager;
    protected TranslationAPIInterface $translationAPI;

    #[Required]
    final public function autowireSchemaDefinitionService(
        RootObjectTypeResolver $rootObjectTypeResolver,
        InstanceManagerInterface $instanceManager,
        AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver,
        TranslationAPIInterface $translationAPI,
    ): void {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
        $this->instanceManager = $instanceManager;
        $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
        $this->translationAPI = $translationAPI;
    }

    public function getRootObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->rootObjectTypeResolver;
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
