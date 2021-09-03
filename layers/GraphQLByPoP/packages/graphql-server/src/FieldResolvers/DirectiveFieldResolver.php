<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use GraphQLByPoP\GraphQLServer\Enums\DirectiveLocationEnum;
use GraphQLByPoP\GraphQLServer\TypeResolvers\DirectiveTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\InputValueTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\FieldResolvers\EnumTypeFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

class DirectiveFieldResolver extends AbstractDBDataFieldResolver
{
    use EnumTypeFieldSchemaDefinitionResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(DirectiveTypeResolver::class);
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

    public function getSchemaFieldType(ObjectTypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'name' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'args' => SchemaDefinition::TYPE_ID,
            'locations' => SchemaDefinition::TYPE_ENUM,
            'isRepeatable' => SchemaDefinition::TYPE_BOOL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'name',
            'isRepeatable'
                => SchemaTypeModifiers::NON_NULLABLE,
            'locations',
            'args'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    protected function getSchemaDefinitionEnumName(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'locations':
                /**
                 * @var DirectiveLocationEnum
                 */
                $directiveLocationEnum = $this->instanceManager->getInstance(DirectiveLocationEnum::class);
                return $directiveLocationEnum->getName();
        }
        return null;
    }

    protected function getSchemaDefinitionEnumValues(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        switch ($fieldName) {
            case 'locations':
                /**
                 * @var DirectiveLocationEnum
                 */
                $directiveLocationEnum = $this->instanceManager->getInstance(DirectiveLocationEnum::class);
                return $directiveLocationEnum->getValues();
        }
        return null;
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'name' => $this->translationAPI->__('Directive\'s name', 'graphql-server'),
            'description' => $this->translationAPI->__('Directive\'s description', 'graphql-server'),
            'args' => $this->translationAPI->__('Directive\'s arguments', 'graphql-server'),
            'locations' => $this->translationAPI->__('The locations where the directive may be placed', 'graphql-server'),
            'isRepeatable' => $this->translationAPI->__('Can the directive be executed more than once in the same field?', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $directive = $resultItem;
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

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'args':
                return InputValueTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
