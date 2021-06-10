<?php

declare(strict_types=1);

namespace PoP\Engine\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractGlobalFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\Dataloading\Expressions;
use PoP\FieldQuery\QueryHelpers;

class FunctionGlobalFieldResolver extends AbstractGlobalFieldResolver
{
    public function getFieldNamesToResolve(): array
    {
        return [
            'getSelfProp',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'getSelfProp' => SchemaDefinition::TYPE_MIXED,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        switch ($fieldName) {
            case 'getSelfProp':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'getSelfProp' => sprintf(
                $this->translationAPI->__('Get a property from the current object, as stored under expression `%s`', 'pop-component-model'),
                QueryHelpers::getExpressionQuery(Expressions::NAME_SELF)
            ),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
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
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
