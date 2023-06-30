<?php

declare(strict_types=1);

namespace PoPAPI\API\Schema;

use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\Engine\Cache\CacheUtils;
use PoP\Engine\Schema\SchemaDefinitionService as UpstreamSchemaDefinitionService;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;
use PoP\Root\Exception\ImpossibleToHappenException;
use PoPAPI\API\Cache\CacheTypes;
use PoPAPI\API\Module;
use PoPAPI\API\ModuleConfiguration;
use PoPAPI\API\ObjectModels\SchemaDefinition\DirectiveSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\EnumTypeSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\InputObjectTypeSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\InterfaceTypeSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\ObjectTypeSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\RootObjectTypeSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\ScalarTypeSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\TypeSchemaDefinitionProviderInterface;
use PoPAPI\API\ObjectModels\SchemaDefinition\UnionTypeSchemaDefinitionProvider;
use PoPAPI\API\PersistedQueries\PersistedFragmentManagerInterface;
use PoPAPI\API\PersistedQueries\PersistedQueryManagerInterface;

class SchemaDefinitionService extends UpstreamSchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    /**
     * Starting from the Root TypeResolver, iterate and get the
     * SchemaDefinition for all TypeResolvers and DirectiveResolvers
     * accessed in the schema
     *
     * @var array<class-string<TypeResolverInterface|FieldDirectiveResolverInterface>>
     */
    private array $processedTypeAndFieldDirectiveResolverClasses = [];
    /** @var array<TypeResolverInterface|FieldDirectiveResolverInterface> */
    private array $pendingTypeOrFieldDirectiveResolvers = [];
    /** @var array<string,RelationalTypeResolverInterface> Key: directive resolver class, Value: The Type Resolver Class which loads the directive */
    private array $accessedFieldDirectiveResolverClassRelationalTypeResolvers = [];
    /** @var array<string,ObjectTypeResolverInterface[]> Key: InterfaceType name, Value: List of ObjectType resolvers implementing the interface */
    private array $accessedInterfaceTypeNameObjectTypeResolvers = [];

    private ?PersistentCacheInterface $persistentCache = null;
    private ?PersistedFragmentManagerInterface $persistedFragmentManager = null;
    private ?PersistedQueryManagerInterface $persistedQueryManager = null;

    /**
     * Cannot autowire with "#[Required]" because its calling `getNamespace`
     * on services.yaml produces an exception of PHP properties not initialized
     * in its depended services.
     */
    final public function setPersistentCache(PersistentCacheInterface $persistentCache): void
    {
        $this->persistentCache = $persistentCache;
    }
    final public function getPersistentCache(): PersistentCacheInterface
    {
        if ($this->persistentCache === null) {
            /** @var PersistentCacheInterface */
            $persistentCache = $this->instanceManager->getInstance(PersistentCacheInterface::class);
            $this->persistentCache = $persistentCache;
        }
        return $this->persistentCache;
    }
    final public function setPersistedFragmentManager(PersistedFragmentManagerInterface $persistedFragmentManager): void
    {
        $this->persistedFragmentManager = $persistedFragmentManager;
    }
    final protected function getPersistedFragmentManager(): PersistedFragmentManagerInterface
    {
        if ($this->persistedFragmentManager === null) {
            /** @var PersistedFragmentManagerInterface */
            $persistedFragmentManager = $this->instanceManager->getInstance(PersistedFragmentManagerInterface::class);
            $this->persistedFragmentManager = $persistedFragmentManager;
        }
        return $this->persistedFragmentManager;
    }
    final public function setPersistedQueryManager(PersistedQueryManagerInterface $persistedQueryManager): void
    {
        $this->persistedQueryManager = $persistedQueryManager;
    }
    final protected function getPersistedQueryManager(): PersistedQueryManagerInterface
    {
        if ($this->persistedQueryManager === null) {
            /** @var PersistedQueryManagerInterface */
            $persistedQueryManager = $this->instanceManager->getInstance(PersistedQueryManagerInterface::class);
            $this->persistedQueryManager = $persistedQueryManager;
        }
        return $this->persistedQueryManager;
    }

    /**
     * @return array<string,mixed>
     */
    public function &getFullSchemaDefinition(): array
    {
        $schemaDefinition = null;
        // Attempt to retrieve from the cache, if enabled
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($useCache = $moduleConfiguration->useSchemaDefinitionCache()) {
            $persistentCache = $this->getPersistentCache();
            // Use different caches for the normal and namespaced schemas, or
            // it throws exception if switching without deleting the cache (eg: when passing ?use_namespace=1)
            $cacheType = CacheTypes::FULLSCHEMA_DEFINITION;
            $cacheKeyElements = CacheUtils::getSchemaCacheKeyElements();
            // For the persistentCache, use a hash to remove invalid characters (such as "()")
            $cacheKey = hash('md5', (string)json_encode($cacheKeyElements));
            if ($persistentCache->hasCache($cacheKey, $cacheType)) {
                $schemaDefinition = $persistentCache->getCache($cacheKey, $cacheType);
            }
        }
        if ($schemaDefinition === null) {
            $schemaDefinition = [
                SchemaDefinition::QUERY_TYPE => $this->getSchemaRootObjectTypeResolver()->getMaybeNamespacedTypeName(),
                SchemaDefinition::TYPES => [],
            ];

            $this->processedTypeAndFieldDirectiveResolverClasses = [];
            $this->accessedFieldDirectiveResolverClassRelationalTypeResolvers = [];
            $this->accessedInterfaceTypeNameObjectTypeResolvers = [];

            $this->pendingTypeOrFieldDirectiveResolvers = [
                $this->getSchemaRootObjectTypeResolver(),
            ];
            while (!empty($this->pendingTypeOrFieldDirectiveResolvers)) {
                $typeOrFieldDirectiveResolver = array_pop($this->pendingTypeOrFieldDirectiveResolvers);
                $this->processedTypeAndFieldDirectiveResolverClasses[] = $typeOrFieldDirectiveResolver::class;
                if ($typeOrFieldDirectiveResolver instanceof TypeResolverInterface) {
                    /** @var TypeResolverInterface */
                    $typeResolver = $typeOrFieldDirectiveResolver;
                    $this->addTypeSchemaDefinition(
                        $typeResolver,
                        $schemaDefinition,
                    );
                } else {
                    /** @var FieldDirectiveResolverInterface */
                    $directiveResolver = $typeOrFieldDirectiveResolver;
                    $this->addDirectiveSchemaDefinition(
                        $directiveResolver,
                        $schemaDefinition,
                    );
                }
            }

            /**
             * Inject this ObjectTypeResolver into the POSSIBLE_TYPES from
             * its implemented InterfaceTypes.
             *
             * Watch out! This logic is implemented like this,
             * instead of retrieving them from the typeRegistry already
             * within InterfaceTypeSchemaDefinitionProvider,
             * because types which are not registered in the schema
             * (such as QueryRoot with nested mutations enabled)
             * must not be processed, yet they are still in typeRegistry
             */
            foreach ($this->accessedInterfaceTypeNameObjectTypeResolvers as $interfaceTypeName => $objectTypeResolvers) {
                $interfaceTypeSchemaDefinition = &$schemaDefinition[SchemaDefinition::TYPES][TypeKinds::INTERFACE][$interfaceTypeName];
                foreach ($objectTypeResolvers as $objectTypeResolver) {
                    $objectTypeName = $objectTypeResolver->getMaybeNamespacedTypeName();
                    $objectTypeSchemaDefinition = [
                        SchemaDefinition::TYPE_RESOLVER => $objectTypeResolver,
                    ];
                    SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($objectTypeSchemaDefinition);
                    $interfaceTypeSchemaDefinition[SchemaDefinition::POSSIBLE_TYPES][$objectTypeName] = $objectTypeSchemaDefinition;
                }
            }

            // Add the Fragment Catalogue
            $schemaDefinition[SchemaDefinition::PERSISTED_FRAGMENTS] = $this->getPersistedFragmentManager()->getPersistedFragmentsForSchema();

            // Add the Query Catalogue
            $schemaDefinition[SchemaDefinition::PERSISTED_QUERIES] = $this->getPersistedQueryManager()->getPersistedQueriesForSchema();

            // Schema extensions
            $schemaDefinition[SchemaDefinition::EXTENSIONS] = $this->getSchemaExtensions();

            // Sort the elements in the schema alphabetically
            if ($moduleConfiguration->sortFullSchemaAlphabetically()) {
                $this->sortFullSchemaAlphabetically($schemaDefinition);
            }

            // Store in the cache
            if ($useCache) {
                $persistentCache->storeCache($cacheKey, $cacheType, $schemaDefinition);
            }
        }

        return $schemaDefinition;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getSchemaExtensions(): array
    {
        return [
            SchemaDefinition::SCHEMA_IS_NAMESPACED => App::getState('namespace-types-and-interfaces'),
        ];
    }

    /**
     * @param array<string,mixed> $schemaDefinition
     */
    public function sortFullSchemaAlphabetically(array &$schemaDefinition): void
    {
        // Sort types
        /** @var string $typeKind */
        foreach (array_keys($schemaDefinition[SchemaDefinition::TYPES]) as $typeKind) {
            ksort($schemaDefinition[SchemaDefinition::TYPES][$typeKind]);
        }

        // Sort fields and interfaces for each ObjectType
        /** @var string $typeName */
        foreach (array_keys($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::OBJECT]) as $typeName) {
            if (isset($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::OBJECT][$typeName][SchemaDefinition::FIELDS])) {
                ksort($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::OBJECT][$typeName][SchemaDefinition::FIELDS]);
            }
            if (isset($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::OBJECT][$typeName][SchemaDefinition::INTERFACES])) {
                ksort($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::OBJECT][$typeName][SchemaDefinition::INTERFACES]);
            }
        }

        // Sort global fields
        if (isset($schemaDefinition[SchemaDefinition::GLOBAL_FIELDS])) {
            ksort($schemaDefinition[SchemaDefinition::GLOBAL_FIELDS]);
        }

        foreach (array_keys($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::UNION] ?? []) as $typeName) {
            ksort($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::UNION][$typeName][SchemaDefinition::POSSIBLE_TYPES]);
        }

        // Sort fields for each InterfaceType
        /** @var string $typeName */
        foreach (array_keys($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::INTERFACE] ?? []) as $typeName) {
            if (isset($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::INTERFACE][$typeName][SchemaDefinition::FIELDS])) {
                ksort($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::INTERFACE][$typeName][SchemaDefinition::FIELDS]);
            }
            if (isset($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::INTERFACE][$typeName][SchemaDefinition::INTERFACES])) {
                ksort($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::INTERFACE][$typeName][SchemaDefinition::INTERFACES]);
            }
            ksort($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::INTERFACE][$typeName][SchemaDefinition::POSSIBLE_TYPES]);
        }

        // Sort input fields for each InputObjectType
        /** @var string $typeName */
        foreach (array_keys($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::INPUT_OBJECT] ?? []) as $typeName) {
            if (isset($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::INPUT_OBJECT][$typeName][SchemaDefinition::INPUT_FIELDS])) {
                ksort($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::INPUT_OBJECT][$typeName][SchemaDefinition::INPUT_FIELDS]);
            }
        }

        // Sort values for each EnumType
        /** @var string $typeName */
        foreach (array_keys($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::ENUM] ?? []) as $typeName) {
            if (isset($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::ENUM][$typeName][SchemaDefinition::ITEMS])) {
                ksort($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::ENUM][$typeName][SchemaDefinition::ITEMS]);
            }
        }

        // Sort possibleValues for each "Enum String" Type
        /** @var string $typeName */
        foreach (array_keys($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::SCALAR] ?? []) as $typeName) {
            if (isset($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::SCALAR][$typeName][SchemaDefinition::POSSIBLE_VALUES])) {
                ksort($schemaDefinition[SchemaDefinition::TYPES][TypeKinds::SCALAR][$typeName][SchemaDefinition::POSSIBLE_VALUES]);
            }
        }

        // Sort directives
        if (isset($schemaDefinition[SchemaDefinition::DIRECTIVES])) {
            ksort($schemaDefinition[SchemaDefinition::DIRECTIVES]);
        }
        if (isset($schemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES])) {
            ksort($schemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES]);
        }
    }

    /**
     * @param array<TypeResolverInterface|FieldDirectiveResolverInterface> $accessedTypeAndFieldDirectiveResolvers
     */
    private function addAccessedTypeAndFieldDirectiveResolvers(
        array $accessedTypeAndFieldDirectiveResolvers,
    ): void {
        // Add further accessed TypeResolvers and DirectiveResolvers to the stack and keep iterating
        foreach ($accessedTypeAndFieldDirectiveResolvers as $accessedTypeOrFieldDirectiveResolver) {
            if (in_array($accessedTypeOrFieldDirectiveResolver::class, $this->processedTypeAndFieldDirectiveResolverClasses)) {
                continue;
            }
            $this->pendingTypeOrFieldDirectiveResolvers[] = $accessedTypeOrFieldDirectiveResolver;
        }
    }

    /**
     * @param array<string,mixed> $schemaDefinition
     */
    private function addTypeSchemaDefinition(
        TypeResolverInterface $typeResolver,
        array &$schemaDefinition,
    ): void {
        $schemaDefinitionProvider = $this->getTypeResolverSchemaDefinitionProvider($typeResolver);
        $typeKind = $schemaDefinitionProvider->getTypeKind();
        $typeName = $typeResolver->getMaybeNamespacedTypeName();
        $typeSchemaDefinition = $schemaDefinitionProvider->getSchemaDefinition();
        /**
         * The RootObject has the special role of also calculating the
         * global fields, connections and directives
         */
        if ($typeResolver === $this->getSchemaRootObjectTypeResolver()) {
            $this->maybeMoveGlobalTypeSchemaDefinition($schemaDefinition, $typeSchemaDefinition);
        }
        $schemaDefinition[SchemaDefinition::TYPES][$typeKind][$typeName] = $typeSchemaDefinition;

        $this->addAccessedTypeAndFieldDirectiveResolvers(
            $schemaDefinitionProvider->getAccessedTypeAndFieldDirectiveResolvers(),
        );
        $this->accessedFieldDirectiveResolverClassRelationalTypeResolvers = array_merge(
            $this->accessedFieldDirectiveResolverClassRelationalTypeResolvers,
            $schemaDefinitionProvider->getAccessedFieldDirectiveResolverClassRelationalTypeResolvers(),
        );
        /**
         * ObjectTypeResolvers must be injected into the POSSIBLE_TYPES of their implemented InterfaceTypes
         */
        if ($typeResolver instanceof ObjectTypeResolverInterface) {
            /** @var ObjectTypeResolverInterface */
            $objectTypeResolver = $typeResolver;
            /** @var ObjectTypeSchemaDefinitionProvider */
            $objectTypeSchemaDefinitionProvider = $schemaDefinitionProvider;
            foreach ($objectTypeSchemaDefinitionProvider->getImplementedInterfaceTypeResolvers() as $implementedInterfaceTypeResolver) {
                $implementedInterfaceTypeName = $implementedInterfaceTypeResolver->getMaybeNamespacedTypeName();
                $this->accessedInterfaceTypeNameObjectTypeResolvers[$implementedInterfaceTypeName] ??= [];
                $this->accessedInterfaceTypeNameObjectTypeResolvers[$implementedInterfaceTypeName][] = $objectTypeResolver;
            }
        }
    }

    /**
     * Move the definition for the global fields, connections and directives
     * @param array<string,mixed> $schemaDefinition
     * @param array<string,mixed> $rootTypeSchemaDefinition
     */
    private function maybeMoveGlobalTypeSchemaDefinition(array &$schemaDefinition, array &$rootTypeSchemaDefinition): void
    {
        unset($rootTypeSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES]);
        if ($this->skipExposingGlobalFieldsInSchema()) {
            return;
        }
        $schemaDefinition[SchemaDefinition::GLOBAL_FIELDS] = $rootTypeSchemaDefinition[SchemaDefinition::GLOBAL_FIELDS];
        unset($rootTypeSchemaDefinition[SchemaDefinition::GLOBAL_FIELDS]);
    }

    /**
     * Global fields are only added if enabled
     */
    protected function skipExposingGlobalFieldsInSchema(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->skipExposingGlobalFieldsInFullSchema();
    }

    /**
     * @param array<string,mixed> $schemaDefinition
     */
    private function addDirectiveSchemaDefinition(
        FieldDirectiveResolverInterface $directiveResolver,
        array &$schemaDefinition,
    ): void {
        $relationalTypeResolver = $this->accessedFieldDirectiveResolverClassRelationalTypeResolvers[$directiveResolver::class];
        $schemaDefinitionProvider = new DirectiveSchemaDefinitionProvider($directiveResolver, $relationalTypeResolver);
        $directiveName = $directiveResolver->getDirectiveName();
        $directiveSchemaDefinition = $schemaDefinitionProvider->getSchemaDefinition();
        $entry = $directiveSchemaDefinition[SchemaDefinition::DIRECTIVE_IS_GLOBAL]
            ? SchemaDefinition::GLOBAL_DIRECTIVES
            : SchemaDefinition::DIRECTIVES;
        $schemaDefinition[$entry][$directiveName] = $directiveSchemaDefinition;

        $this->addAccessedTypeAndFieldDirectiveResolvers(
            $schemaDefinitionProvider->getAccessedTypeAndFieldDirectiveResolvers()
        );
    }

    /**
     * @throws ImpossibleToHappenException If the TypeResolver does not belong to any of the known groups
     */
    protected function getTypeResolverSchemaDefinitionProvider(
        TypeResolverInterface $typeResolver,
    ): TypeSchemaDefinitionProviderInterface {
        if ($typeResolver instanceof ObjectTypeResolverInterface) {
            /**
             * The RootObject has the special role of also calculating the
             * global fields, connections and directives
             */
            if ($typeResolver === $this->getSchemaRootObjectTypeResolver()) {
                return $this->createRootObjectTypeSchemaDefinitionProvider($typeResolver);
            }
            return new ObjectTypeSchemaDefinitionProvider($typeResolver);
        }
        if ($typeResolver instanceof InterfaceTypeResolverInterface) {
            return new InterfaceTypeSchemaDefinitionProvider($typeResolver);
        }
        if ($typeResolver instanceof UnionTypeResolverInterface) {
            return new UnionTypeSchemaDefinitionProvider($typeResolver);
        }
        if ($typeResolver instanceof ScalarTypeResolverInterface) {
            return new ScalarTypeSchemaDefinitionProvider($typeResolver);
        }
        if ($typeResolver instanceof EnumTypeResolverInterface) {
            return new EnumTypeSchemaDefinitionProvider($typeResolver);
        }
        if ($typeResolver instanceof InputObjectTypeResolverInterface) {
            return new InputObjectTypeSchemaDefinitionProvider($typeResolver);
        }
        throw new ImpossibleToHappenException(sprintf(
            $this->__('No type identified for TypeResolver with class \'%s\'', 'api'),
            get_class($typeResolver)
        ));
    }

    protected function createRootObjectTypeSchemaDefinitionProvider(
        RootObjectTypeResolver $rootObjectTypeResolver,
    ): RootObjectTypeSchemaDefinitionProvider {
        return new RootObjectTypeSchemaDefinitionProvider($rootObjectTypeResolver);
    }
}
