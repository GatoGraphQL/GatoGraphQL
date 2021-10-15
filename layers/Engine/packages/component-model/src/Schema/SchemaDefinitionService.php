<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyScalarScalarTypeResolver;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    protected AnyScalarScalarTypeResolver $anyScalarScalarTypeResolver;
    protected InstanceManagerInterface $instanceManager;
    protected TranslationAPIInterface $translationAPI;

    #[Required]
    final public function autowireSchemaDefinitionService(
        InstanceManagerInterface $instanceManager,
        AnyScalarScalarTypeResolver $anyScalarScalarTypeResolver,
        TranslationAPIInterface $translationAPI,
    ): void {
        $this->instanceManager = $instanceManager;
        $this->anyScalarScalarTypeResolver = $anyScalarScalarTypeResolver;
        $this->translationAPI = $translationAPI;
    }

    /**
     * The `AnyScalar` type is a wildcard type,
     * representing *any* type (string, int, bool, etc)
     */
    public function getDefaultConcreteTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->anyScalarScalarTypeResolver;
    }
    /**
     * The `AnyScalar` type is a wildcard type,
     * representing *any* type (string, int, bool, etc)
     */
    public function getDefaultInputTypeResolver(): InputTypeResolverInterface
    {
        return $this->anyScalarScalarTypeResolver;
    }
}
