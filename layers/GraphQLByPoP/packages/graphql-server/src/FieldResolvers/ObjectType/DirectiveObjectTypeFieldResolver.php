<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\Enums\DirectiveLocationEnum;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\WithEnumObjectTypeFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class DirectiveObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use WithEnumObjectTypeFieldSchemaDefinitionResolverTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            DirectiveObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'description',
            'args',
            'locations',
            'isRepeatable',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        return match ($fieldName) {
            'name' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'locations' => SchemaDefinition::TYPE_ENUM,
            'isRepeatable' => SchemaDefinition::TYPE_BOOL,
            default => parent::getSchemaFieldType($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'name',
            'isRepeatable'
                => SchemaTypeModifiers::NON_NULLABLE,
            'locations',
            'args'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    protected function getSchemaDefinitionEnumName(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'locations':
                /**
                 * @var DirectiveLocationEnum
                 */
                $directiveLocationEnum = $this->instanceManager->getInstance(DirectiveLocationEnum::class);
                return $directiveLocationEnum->getTypeName();
        }
        return null;
    }

    protected function getSchemaDefinitionEnumValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        switch ($fieldName) {
            case 'locations':
                /**
                 * @var DirectiveLocationEnum
                 */
                $directiveLocationEnum = $this->instanceManager->getInstance(DirectiveLocationEnum::class);
                return $directiveLocationEnum->getEnumValues();
        }
        return null;
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'name' => $this->translationAPI->__('Directive\'s name', 'graphql-server'),
            'description' => $this->translationAPI->__('Directive\'s description', 'graphql-server'),
            'args' => $this->translationAPI->__('Directive\'s arguments', 'graphql-server'),
            'locations' => $this->translationAPI->__('The locations where the directive may be placed', 'graphql-server'),
            'isRepeatable' => $this->translationAPI->__('Can the directive be executed more than once in the same field?', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
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
        $directive = $object;
        switch ($fieldName) {
            case 'name':
                return $directive->getName();
            case 'description':
                return $directive->getDescription();
            case 'args':
                return $directive->getArgIDs();
            case 'locations':
                return $directive->getLocations();
            case 'isRepeatable':
                return $directive->isRepeatable();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'args':
                return InputValueObjectTypeResolver::class;
        }
        return parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }
}
