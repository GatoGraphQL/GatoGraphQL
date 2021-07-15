<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Engine\FieldResolvers\OperatorGlobalFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;

/**
 * When Embeddable Fields is enabled, register the `echoStr` field
 */
class EchoOperatorGlobalFieldResolver extends OperatorGlobalFieldResolver
{
    /**
     * Only use it when "embeddable fields" is enabled.
     *
     * Check on runtime (not via container) since this option can be
     * assigned to the Schema Configuration in the GraphQL API plugin.
     */
    public function isServiceEnabled(): bool
    {
        return APIComponentConfiguration::enableEmbeddableFields();
    }

    /**
     * By making it not global, it gets registered on each single type.
     * Otherwise, it is not exposed in the schema
     */
    public function isGlobal(TypeResolverInterface $typeResolver, string $fieldName): bool
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
    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'echoStr' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    /**
     * Change the type from mixed to string
     */
    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
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
    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'echoStr' => $this->translationAPI->__('Repeat back the input string', 'graphql-api'),
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
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

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
