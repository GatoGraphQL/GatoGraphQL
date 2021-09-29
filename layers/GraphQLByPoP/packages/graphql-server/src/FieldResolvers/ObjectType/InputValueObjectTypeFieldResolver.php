<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\InputValue;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class InputValueObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected TypeObjectTypeResolver $typeObjectTypeResolver;

    #[Required]
    public function autowireInputValueObjectTypeFieldResolver(
        StringScalarTypeResolver $stringScalarTypeResolver,
        TypeObjectTypeResolver $typeObjectTypeResolver,
    ): void {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->typeObjectTypeResolver = $typeObjectTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            InputValueObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'description',
            'type',
            'defaultValue',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name' => $this->stringScalarTypeResolver,
            'description' => $this->stringScalarTypeResolver,
            'defaultValue' => $this->stringScalarTypeResolver,
            'type' => $this->typeObjectTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'name',
            'type',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->translationAPI->__('Input value\'s name as defined by the GraphQL spec', 'graphql-server'),
            'description' => $this->translationAPI->__('Input value\'s description', 'graphql-server'),
            'type' => $this->translationAPI->__('Type of the input value', 'graphql-server'),
            'defaultValue' => $this->translationAPI->__('Default value of the input value', 'graphql-server'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
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
        /** @var InputValue */
        $inputValue = $object;
        switch ($fieldName) {
            case 'name':
                return $inputValue->getName();
            case 'description':
                return $inputValue->getDescription();
            case 'type':
                return $inputValue->getType()->getID();
            case 'defaultValue':
                return $inputValue->getDefaultValue();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
