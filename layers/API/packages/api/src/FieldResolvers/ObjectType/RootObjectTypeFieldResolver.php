<?php

declare(strict_types=1);

namespace PoP\API\FieldResolvers\ObjectType;

use PoP\API\Cache\CacheTypes;
use PoP\API\ComponentConfiguration;
use PoP\API\PersistedQueries\PersistedFragmentManagerInterface;
use PoP\API\PersistedQueries\PersistedQueryManagerInterface;
use PoP\API\Schema\SchemaDefinition;
use PoP\API\TypeResolvers\EnumType\SchemaFieldShapeEnumTypeResolver;
use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinitionShapes;
use PoP\ComponentModel\Schema\SchemaDefinitionTypes;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Cache\CacheUtils;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\ObjectScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * Cannot autowire because its calling `getNamespace`
     * on services.yaml produces an exception of PHP properties not initialized
     * in its depended services.
     */
    protected ?PersistentCacheInterface $persistentCache = null;

    protected SchemaFieldShapeEnumTypeResolver $schemaOutputShapeEnumTypeResolver;
    protected ObjectScalarTypeResolver $objectScalarTypeResolver;
    protected PersistedFragmentManagerInterface $fragmentCatalogueManager;
    protected PersistedQueryManagerInterface $queryCatalogueManager;
    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;

    #[Required]
    final public function autowireRootObjectTypeFieldResolver(
        SchemaFieldShapeEnumTypeResolver $schemaOutputShapeEnumTypeResolver,
        ObjectScalarTypeResolver $objectScalarTypeResolver,
        PersistedFragmentManagerInterface $fragmentCatalogueManager,
        PersistedQueryManagerInterface $queryCatalogueManager,
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
    ): void {
        $this->schemaOutputShapeEnumTypeResolver = $schemaOutputShapeEnumTypeResolver;
        $this->objectScalarTypeResolver = $objectScalarTypeResolver;
        $this->fragmentCatalogueManager = $fragmentCatalogueManager;
        $this->queryCatalogueManager = $queryCatalogueManager;
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }

    final public function getPersistentCache(): PersistentCacheInterface
    {
        $this->persistentCache ??= PersistentCacheFacade::getInstance();
        return $this->persistentCache;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'fullSchema',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'fullSchema' => $this->objectScalarTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'fullSchema' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'fullSchema' => $this->translationAPI->__('The whole API schema, exposing what fields can be queried', 'api'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'fullSchema' => [
                'deep' => $this->booleanScalarTypeResolver,
                'shape' => $this->schemaOutputShapeEnumTypeResolver,
                'compressed' => $this->booleanScalarTypeResolver,
                'useTypeName' => $this->booleanScalarTypeResolver,
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['fullSchema' => 'deep'] => $this->translationAPI->__('Make a deep introspection of the fields, for all nested objects', 'api'),
            ['fullSchema' => 'shape'] => sprintf(
                $this->translationAPI->__('How to shape the schema output: \'%s\', in which case all types are listed together, or \'%s\', in which the types are listed following where they appear in the graph', 'api'),
                SchemaDefinitionShapes::FLAT,
                SchemaDefinitionShapes::NESTED
            ),
            ['fullSchema' => 'compressed'] => $this->translationAPI->__('Output each resolver\'s schema data only once to compress the output. Valid only when field \'deep\' is `true`', 'api'),
            ['fullSchema' => 'useTypeName'] => sprintf(
                $this->translationAPI->__('Replace type \'%s\' with the actual type name (such as \'Post\')', 'api'),
                SchemaDefinitionTypes::TYPE_ID
            ),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ([$fieldName => $fieldArgName]) {
            ['fullSchema' => 'deep'] => true,
            ['fullSchema' => 'shape'] => SchemaDefinitionShapes::FLAT,
            ['fullSchema' => 'compressed'] => false,
            ['fullSchema' => 'useTypeName'] => true,
            default => parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
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
            case 'fullSchema':
                // Attempt to retrieve from the cache, if enabled
                if ($useCache = ComponentConfiguration::useSchemaDefinitionCache()) {
                    // Use different caches for the normal and namespaced schemas, or
                    // it throws exception if switching without deleting the cache (eg: when passing ?use_namespace=1)
                    $cacheType = CacheTypes::FULLSCHEMA_DEFINITION;
                    $cacheKeyComponents = CacheUtils::getSchemaCacheKeyComponents();
                    // For the persistentCache, use a hash to remove invalid characters (such as "()")
                    $cacheKey = hash('md5', json_encode($cacheKeyComponents));
                }
                $schemaDefinition = null;
                if ($useCache) {
                    if ($this->getPersistentCache()->hasCache($cacheKey, $cacheType)) {
                        $schemaDefinition = $this->getPersistentCache()->getCache($cacheKey, $cacheType);
                    }
                }
                if ($schemaDefinition === null) {
                    $stackMessages = [
                        'processed' => [],
                    ];
                    $generalMessages = [
                        'processed' => [],
                    ];
                    $rootTypeSchemaKey = $this->schemaDefinitionService->getTypeSchemaKey($objectTypeResolver);
                    // Normalize properties in $fieldArgs with their defaults
                    // By default make it deep. To avoid it, must pass argument (deep:false)
                    // By default, use the "flat" shape
                    $schemaOptions = array_merge(
                        $options,
                        [
                            'deep' => $fieldArgs['deep'],
                            'compressed' => $fieldArgs['compressed'],
                            'shape' => $fieldArgs['shape'],
                            'useTypeName' => $fieldArgs['useTypeName'],
                        ]
                    );
                    // If it is flat shape, all types will be added under $generalMessages
                    $isFlatShape = $schemaOptions['shape'] == SchemaDefinitionShapes::FLAT;
                    if ($isFlatShape) {
                        $generalMessages[SchemaDefinition::TYPES] = [];
                    }
                    $typeSchemaDefinition = $objectTypeResolver->getSchemaDefinition($stackMessages, $generalMessages, $schemaOptions);
                    $schemaDefinition[SchemaDefinition::TYPES] = $typeSchemaDefinition;

                    // Add the queryType
                    $schemaDefinition[SchemaDefinition::QUERY_TYPE] = $rootTypeSchemaKey;

                    // Move from under Root type to the top: globalDirectives and globalFields (renamed as "functions")
                    $schemaDefinition[SchemaDefinition::GLOBAL_FIELDS] = $typeSchemaDefinition[$rootTypeSchemaKey][SchemaDefinition::GLOBAL_FIELDS] ?? [];
                    $schemaDefinition[SchemaDefinition::GLOBAL_CONNECTIONS] = $typeSchemaDefinition[$rootTypeSchemaKey][SchemaDefinition::GLOBAL_CONNECTIONS] ?? [];
                    $schemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES] = $typeSchemaDefinition[$rootTypeSchemaKey][SchemaDefinition::GLOBAL_DIRECTIVES] ?? [];
                    unset($schemaDefinition[SchemaDefinition::TYPES][$rootTypeSchemaKey][SchemaDefinition::GLOBAL_FIELDS]);
                    unset($schemaDefinition[SchemaDefinition::TYPES][$rootTypeSchemaKey][SchemaDefinition::GLOBAL_CONNECTIONS]);
                    unset($schemaDefinition[SchemaDefinition::TYPES][$rootTypeSchemaKey][SchemaDefinition::GLOBAL_DIRECTIVES]);

                    // Retrieve the list of all types from under $generalMessages
                    if ($isFlatShape) {
                        $typeFlatList = $generalMessages[SchemaDefinition::TYPES];

                        // Remove the globals from the Root
                        unset($typeFlatList[$rootTypeSchemaKey][SchemaDefinition::GLOBAL_FIELDS]);
                        unset($typeFlatList[$rootTypeSchemaKey][SchemaDefinition::GLOBAL_CONNECTIONS]);
                        unset($typeFlatList[$rootTypeSchemaKey][SchemaDefinition::GLOBAL_DIRECTIVES]);

                        // Because they were added in reverse way, reverse it once again, so that the first types (eg: Root) appear first
                        $schemaDefinition[SchemaDefinition::TYPES] = array_reverse($typeFlatList);

                        // Add the interfaces to the root
                        $interfaces = [];
                        foreach ($schemaDefinition[SchemaDefinition::TYPES] as $typeName => $typeDefinition) {
                            if ($typeInterfaces = $typeDefinition[SchemaDefinition::INTERFACES] ?? null) {
                                $interfaces = array_merge(
                                    $interfaces,
                                    (array)$typeInterfaces
                                );
                                // Keep only the name of the interface under the type
                                $schemaDefinition[SchemaDefinition::TYPES][$typeName][SchemaDefinition::INTERFACES] = array_keys((array)$schemaDefinition[SchemaDefinition::TYPES][$typeName][SchemaDefinition::INTERFACES]);
                            }
                        }
                        $schemaDefinition[SchemaDefinition::INTERFACES] = $interfaces;
                    }

                    // Add the Fragment Catalogue
                    $persistedFragments = $this->fragmentCatalogueManager->getPersistedFragmentsForSchema();
                    $schemaDefinition[SchemaDefinition::PERSISTED_FRAGMENTS] = $persistedFragments;

                    // Add the Query Catalogue
                    $persistedQueries = $this->queryCatalogueManager->getPersistedQueriesForSchema();
                    $schemaDefinition[SchemaDefinition::PERSISTED_QUERIES] = $persistedQueries;

                    // Store in the cache
                    if ($useCache) {
                        $this->getPersistentCache()->storeCache($cacheKey, $cacheType, $schemaDefinition);
                    }
                }

                return $schemaDefinition;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
