<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\EmbeddableFields;

use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Engine\FieldResolvers\ObjectType\OperatorGlobalObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * When Embeddable Fields is enabled, register the `echoStr` field
 */
class EchoOperatorGlobalObjectTypeFieldResolver extends OperatorGlobalObjectTypeFieldResolver
{
    use EmbeddableFieldsObjectTypeFieldResolverTrait;

    /**
     * By making it not global, it gets registered on each single type.
     * Otherwise, it is not exposed in the schema
     */
    public function isGlobal(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return false;
    }

    // /**
    //  * Higher priority => Process before the global fieldResolver,
    //  * so this one gets registered (otherwise, since `ADD_GLOBAL_FIELDS_TO_SCHEMA`
    //  * is false, the field would be removed)
    //  */
    // public function getPriorityToAttachToClasses(): int
    // {
    //     return 20;
    // }

    /**
     * Only the `echo` field is to be exposed
     *
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'echoStr',
        ];
    }

    /**
     * Change the type from mixed to string
     */
    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'echoStr' => StringScalarTypeResolver::class,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }

    /**
     * Change the type from mixed to string
     */
    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'echoStr':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'value',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The input string to be echoed back', 'graphql-api'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    /**
     * Change the type from mixed to string
     */
    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'echoStr' => $this->translationAPI->__('Repeat back the input string', 'graphql-api'),
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
        switch ($fieldName) {
            case 'echoStr':
                return $fieldArgs['value'];
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
