<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\SchemaTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use GraphQLByPoP\GraphQLServer\TypeDataLoaders\SchemaTypeDataLoader;

class RootFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        // Only register them for the standard GraphQL,
        // or for PQL if explicitly enabled
        $vars = ApplicationState::getVars();
        if (!$vars['graphql-introspection-enabled']) {
            return [];
        }
        return [
            '__schema',
            '__type',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            '__schema' => SchemaDefinition::TYPE_ID,
            '__type' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            '__schema',
            '__type',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            '__schema' => $this->translationAPI->__('The GraphQL schema, exposing what fields can be queried', 'graphql-server'),
            '__type' => $this->translationAPI->__('Obtain a specific type from the schema', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case '__type':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'name',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The name of the type', 'graphql-server'),
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
        $root = $resultItem;
        switch ($fieldName) {
            case '__schema':
                return 'schema';
            case '__type':
                // Get an instance of the schema and then execute function `getType` there
                $schemaID = $typeResolver->resolveValue(
                    $resultItem,
                    $this->fieldQueryInterpreter->getField(
                        '__schema',
                        []
                    ),
                    $variables,
                    $expressions,
                    $options
                );
                if (GeneralUtils::isError($schemaID)) {
                    return $schemaID;
                }
                // Obtain the instance of the schema
                /**
                 * @var SchemaTypeDataLoader
                 */
                $schemaTypeDataLoader = $this->instanceManager->getInstance(SchemaTypeDataLoader::class);
                $schemaInstances = $schemaTypeDataLoader->getObjects([$schemaID]);
                $schema = $schemaInstances[0];
                return $schema->getTypeID($fieldArgs['name']);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case '__schema':
                return SchemaTypeResolver::class;
            case '__type':
                return TypeTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
