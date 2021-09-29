<?php

declare(strict_types=1);

namespace PoP\Engine\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractGlobalObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\MixedScalarTypeResolver;
use PoP\Engine\Dataloading\Expressions;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\FieldQuery\QueryHelpers;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\ObjectScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class FunctionGlobalObjectTypeFieldResolver extends AbstractGlobalObjectTypeFieldResolver
{
    protected MixedScalarTypeResolver $mixedScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected ObjectScalarTypeResolver $objectScalarTypeResolver;

    #[Required]
    public function autowireFunctionGlobalObjectTypeFieldResolver(
        MixedScalarTypeResolver $mixedScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
        ObjectScalarTypeResolver $objectScalarTypeResolver,
    ): void {
        $this->mixedScalarTypeResolver = $mixedScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->objectScalarTypeResolver = $objectScalarTypeResolver;
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'getSelfProp',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'getSelfProp' => $this->mixedScalarTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'getSelfProp' => sprintf(
                $this->translationAPI->__('Get a property from the current object, as stored under expression `%s`', 'pop-component-model'),
                QueryHelpers::getExpressionQuery(Expressions::NAME_SELF)
            ),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            default => parent::getSchemaFieldArgNameResolvers($objectTypeResolver, $fieldName),
        };
    }
    
    public function getSchemaFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ($fieldName) {
            default => parent::getSchemaFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ($fieldName) {
            default => parent::getSchemaFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?int
    {
        return match ($fieldName) {
            default => parent::getSchemaFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'getSelfProp':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'self',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_OBJECT,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The `$self` object containing all data for the current object', 'component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'property',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The property to access from the current object', 'component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'getSelfProp':
                // Retrieve the property from either 'dbItems' (i.e. it was loaded during the current iteration)
                // or 'previousDBItems' (loaded during a previous iteration)
                $self = $fieldArgs['self'];
                $property = $fieldArgs['property'];
                return array_key_exists($property, $self['dbItems']) ? $self['dbItems'][$property] : ($self['previousDBItems'][$property] ?? null);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
