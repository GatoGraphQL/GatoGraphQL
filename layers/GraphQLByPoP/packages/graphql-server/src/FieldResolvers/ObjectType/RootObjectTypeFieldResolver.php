<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaTypeDataLoader;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?SchemaObjectTypeResolver $schemaObjectTypeResolver = null;
    private ?TypeObjectTypeResolver $typeObjectTypeResolver = null;
    private ?SchemaTypeDataLoader $schemaTypeDataLoader = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    public function setSchemaObjectTypeResolver(SchemaObjectTypeResolver $schemaObjectTypeResolver): void
    {
        $this->schemaObjectTypeResolver = $schemaObjectTypeResolver;
    }
    protected function getSchemaObjectTypeResolver(): SchemaObjectTypeResolver
    {
        return $this->schemaObjectTypeResolver ??= $this->instanceManager->getInstance(SchemaObjectTypeResolver::class);
    }
    public function setTypeObjectTypeResolver(TypeObjectTypeResolver $typeObjectTypeResolver): void
    {
        $this->typeObjectTypeResolver = $typeObjectTypeResolver;
    }
    protected function getTypeObjectTypeResolver(): TypeObjectTypeResolver
    {
        return $this->typeObjectTypeResolver ??= $this->instanceManager->getInstance(TypeObjectTypeResolver::class);
    }
    public function setSchemaTypeDataLoader(SchemaTypeDataLoader $schemaTypeDataLoader): void
    {
        $this->schemaTypeDataLoader = $schemaTypeDataLoader;
    }
    protected function getSchemaTypeDataLoader(): SchemaTypeDataLoader
    {
        return $this->schemaTypeDataLoader ??= $this->instanceManager->getInstance(SchemaTypeDataLoader::class);
    }
    public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    //#[Required]
    final public function autowireRootObjectTypeFieldResolver(
        SchemaObjectTypeResolver $schemaObjectTypeResolver,
        TypeObjectTypeResolver $typeObjectTypeResolver,
        SchemaTypeDataLoader $schemaTypeDataLoader,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->schemaObjectTypeResolver = $schemaObjectTypeResolver;
        $this->typeObjectTypeResolver = $typeObjectTypeResolver;
        $this->schemaTypeDataLoader = $schemaTypeDataLoader;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
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

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            '__schema',
            '__type'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            '__schema' => $this->translationAPI->__('The GraphQL schema, exposing what fields can be queried', 'graphql-server'),
            '__type' => $this->translationAPI->__('Obtain a specific type from the schema', 'graphql-server'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            '__type' => [
                'name' => $this->getStringScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['__type' => 'name'] => $this->translationAPI->__('The name of the type', 'graphql-server'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['__type' => 'name'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
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
        $root = $object;
        switch ($fieldName) {
            case '__schema':
                return 'schema';
            case '__type':
                // Get an instance of the schema and then execute function `getType` there
                $schemaID = $objectTypeResolver->resolveValue(
                    $object,
                    $this->getFieldQueryInterpreter()->getField(
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
                $schemaInstances = $this->getSchemaTypeDataLoader()->getObjects([$schemaID]);
                $schema = $schemaInstances[0];
                return $schema->getTypeID($fieldArgs['name']);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            '__schema' => $this->getSchemaObjectTypeResolver(),
            '__type' => $this->getTypeObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
