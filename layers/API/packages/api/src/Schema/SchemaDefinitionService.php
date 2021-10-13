<?php

declare(strict_types=1);

namespace PoP\API\Schema;

use Exception;
use PoP\API\Cache\CacheTypes;
use PoP\API\ComponentConfiguration;
use PoP\API\ObjectModels\SchemaDefinition\DirectiveSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\EnumTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\InputObjectTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\InterfaceTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\ObjectTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\RootObjectTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\ScalarTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\TypeSchemaDefinitionProviderInterface;
use PoP\API\ObjectModels\SchemaDefinition\UnionTypeSchemaDefinitionProvider;
use PoP\API\PersistedQueries\PersistedFragmentManagerInterface;
use PoP\API\PersistedQueries\PersistedQueryManagerInterface;
use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
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
use Symfony\Contracts\Service\Attribute\Required;

class SchemaDefinitionService extends UpstreamSchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    /**
     * Starting from the Root TypeResolver, iterate and get the
     * SchemaDefinition for all TypeResolvers and DirectiveResolvers
     * accessed in the schema
     */
    private array $processedTypeAndDirectiveResolverClasses = [];
    /** @var array<TypeResolverInterface|DirectiveResolverInterface> */
    private array $pendingTypeOrDirectiveResolvers = [];
    /** @var array<string, RelationalTypeResolverInterface> Key: directive resolver class, Value: The Type Resolver Class which loads the directive */
    private array $accessedDirectiveResolverClassRelationalTypeResolvers = [];
    
    /**
     * Cannot autowire because its calling `getNamespace`
     * on services.yaml produces an exception of PHP properties not initialized
     * in its depended services.
     */
    protected ?PersistentCacheInterface $persistentCache = null;

    protected PersistedFragmentManagerInterface $fragmentCatalogueManager;
    protected PersistedQueryManagerInterface $queryCatalogueManager;
    protected RootObjectTypeResolver $rootObjectTypeResolver;

    #[Required]
    final public function autowireAPISchemaDefinitionService(
        PersistedFragmentManagerInterface $fragmentCatalogueManager,
        PersistedQueryManagerInterface $queryCatalogueManager,
        RootObjectTypeResolver $rootObjectTypeResolver,
    ): void {
        $this->fragmentCatalogueManager = $fragmentCatalogueManager;
        $this->queryCatalogueManager = $queryCatalogueManager;
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
    }

    final public function getPersistentCache(): PersistentCacheInterface
    {
        $this->persistentCache ??= PersistentCacheFacade::getInstance();
        return $this->persistentCache;
    }

    public function getFullSchemaDefinition(): array
    {
        $schemaDefinition = null;
        // Attempt to retrieve from the cache, if enabled
        if ($useCache = ComponentConfiguration::useSchemaDefinitionCache()) {
            $persistentCache = $this->getPersistentCache();
            // Use different caches for the normal and namespaced schemas, or
            // it throws exception if switching without deleting the cache (eg: when passing ?use_namespace=1)
            $cacheType = CacheTypes::FULLSCHEMA_DEFINITION;
            $cacheKeyComponents = CacheUtils::getSchemaCacheKeyComponents();
            // For the persistentCache, use a hash to remove invalid characters (such as "()")
            $cacheKey = hash('md5', json_encode($cacheKeyComponents));
            if ($persistentCache->hasCache($cacheKey, $cacheType)) {
                $schemaDefinition = $persistentCache->getCache($cacheKey, $cacheType);
            }
        }
        if ($schemaDefinition === null) {
            $rootObjectTypeResolver = $this->getRootTypeResolver();
            $schemaDefinition = [
                SchemaDefinition::QUERY_TYPE => $rootObjectTypeResolver->getMaybeNamespacedTypeName(),
                SchemaDefinition::TYPES => [],
                SchemaDefinition::DIRECTIVES => [],
                SchemaDefinition::GLOBAL_DIRECTIVES => [],
                SchemaDefinition::GLOBAL_FIELDS => [],
                SchemaDefinition::GLOBAL_CONNECTIONS => [],
            ];

            $this->processedTypeAndDirectiveResolverClasses = [];
            $this->accessedDirectiveResolverClassRelationalTypeResolvers = [];

            /** @var array<TypeResolverInterface|DirectiveResolverInterface> */
            $this->pendingTypeOrDirectiveResolvers = $this->getRootObjectTypeResolvers();
            while (!empty($this->pendingTypeOrDirectiveResolvers)) {
                $typeOrDirectiveResolver = array_pop($this->pendingTypeOrDirectiveResolvers);
                $this->processedTypeAndDirectiveResolverClasses[] = $typeOrDirectiveResolver::class;                
                if ($typeOrDirectiveResolver instanceof TypeResolverInterface) {
                    /** @var TypeResolverInterface */
                    $typeResolver = $typeOrDirectiveResolver;
                    $this->addTypeSchemaDefinition(
                        $typeResolver,
                        $schemaDefinition,
                    );
                } else {
                    /** @var DirectiveResolverInterface */
                    $directiveResolver = $typeOrDirectiveResolver;
                    $this->addDirectiveSchemaDefinition(
                        $directiveResolver,
                        $schemaDefinition,
                    );
                }
            }

            // Add the Fragment Catalogue
            $schemaDefinition[SchemaDefinition::PERSISTED_FRAGMENTS] = $this->fragmentCatalogueManager->getPersistedFragmentsForSchema();

            // Add the Query Catalogue
            $schemaDefinition[SchemaDefinition::PERSISTED_QUERIES] = $this->queryCatalogueManager->getPersistedQueriesForSchema();

            // Store in the cache
            if ($useCache) {
                $persistentCache->storeCache($cacheKey, $cacheType, $schemaDefinition);
            }
        }

        return $schemaDefinition;
    }

    private function addAccessedTypeAndDirectiveResolvers(
        array $accessedTypeAndDirectiveResolvers,
    ): void {
        // Add further accessed TypeResolvers and DirectiveResolvers to the stack and keep iterating
        foreach ($accessedTypeAndDirectiveResolvers as $accessedTypeOrDirectiveResolver) {
            if (in_array($accessedTypeOrDirectiveResolver::class, $this->processedTypeAndDirectiveResolverClasses)) {
                continue;
            }
            $this->pendingTypeOrDirectiveResolvers[] = $accessedTypeOrDirectiveResolver;
        }
    }

    /**
     * @return array<TypeResolverInterface|DirectiveResolverInterface> Accessed Type and Directive Resolvers
     */
    private function addTypeSchemaDefinition(
        TypeResolverInterface $typeResolver,
        array &$schemaDefinition,
    ): void {
        $schemaDefinitionProvider = $this->getTypeResolverSchemaDefinitionProvider($typeResolver);
        $type = $schemaDefinitionProvider->getType();
        $typeName = $typeResolver->getMaybeNamespacedTypeName();
        $typeSchemaDefinition = $schemaDefinitionProvider->getSchemaDefinition();
        /**
         * The RootObject has the special role of also calculating the
         * global fields, connections and directives
         */
        if (in_array($typeResolver, $this->getRootObjectTypeResolvers())) {
            $this->moveGlobalTypeSchemaDefinition($schemaDefinition, $typeSchemaDefinition);
        }
        $schemaDefinition[SchemaDefinition::TYPES][$type][$typeName] = $typeSchemaDefinition;

        $this->addAccessedTypeAndDirectiveResolvers(
            $schemaDefinitionProvider->getAccessedTypeAndDirectiveResolvers(),
        );
        $this->accessedDirectiveResolverClassRelationalTypeResolvers = array_merge(
            $this->accessedDirectiveResolverClassRelationalTypeResolvers,
            $schemaDefinitionProvider->getAccessedDirectiveResolverClassRelationalTypeResolvers(),
        );
    }

    /**
     * Move the definition for the global fields, connections and directives
     */
    private function moveGlobalTypeSchemaDefinition(array &$schemaDefinition, array &$rootTypeSchemaDefinition): void
    {
        $schemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES] = array_merge(
            $schemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES],
            $rootTypeSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES]
        );
        $schemaDefinition[SchemaDefinition::GLOBAL_FIELDS] = array_merge(
            $schemaDefinition[SchemaDefinition::GLOBAL_FIELDS],
            $rootTypeSchemaDefinition[SchemaDefinition::GLOBAL_FIELDS]
        );
        $schemaDefinition[SchemaDefinition::GLOBAL_CONNECTIONS] = array_merge(
            $schemaDefinition[SchemaDefinition::GLOBAL_CONNECTIONS],
            $rootTypeSchemaDefinition[SchemaDefinition::GLOBAL_CONNECTIONS]
        );
        unset($rootTypeSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES]);
        unset($rootTypeSchemaDefinition[SchemaDefinition::GLOBAL_FIELDS]);
        unset($rootTypeSchemaDefinition[SchemaDefinition::GLOBAL_CONNECTIONS]);
    }

    /**
     * @return array<TypeResolverInterface|DirectiveResolverInterface> Accessed Type and Directive Resolvers
     */
    private function addDirectiveSchemaDefinition(
        DirectiveResolverInterface $directiveResolver,
        array &$schemaDefinition,
    ): void {
        $relationalTypeResolver = $this->accessedDirectiveResolverClassRelationalTypeResolvers[$directiveResolver::class];
        $schemaDefinitionProvider = new DirectiveSchemaDefinitionProvider($directiveResolver, $relationalTypeResolver);
        $directiveName = $directiveResolver->getDirectiveName();
        $directiveSchemaDefinition = $schemaDefinitionProvider->getSchemaDefinition();
        $schemaDefinition[SchemaDefinition::DIRECTIVES][$directiveName] = $directiveSchemaDefinition;

        $this->addAccessedTypeAndDirectiveResolvers(
            $schemaDefinitionProvider->getAccessedTypeAndDirectiveResolvers()
        );
    }

    /**
     * @throws Exception If the TypeResolver does not belong to any of the known groups
     */
    protected function getTypeResolverSchemaDefinitionProvider(TypeResolverInterface $typeResolver): TypeSchemaDefinitionProviderInterface
    {
        /**
         * The RootObject has the special role of also calculating the
         * global fields, connections and directives
         */
        if (in_array($typeResolver, $this->getRootObjectTypeResolvers())) {
            return new RootObjectTypeSchemaDefinitionProvider($typeResolver);
        }
        if ($typeResolver instanceof ObjectTypeResolverInterface) {
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
        throw new Exception(sprintf(
            $this->translationAPI->__('No type identified for TypeResolver with class \'%s\'', 'api'),
            get_class($typeResolver)
        ));
    }

    /**
     * @return ObjectTypeResolverInterface[]
     */
    protected function getRootObjectTypeResolvers(): array
    {
        return [
            $this->rootObjectTypeResolver,
        ];
    }
}
