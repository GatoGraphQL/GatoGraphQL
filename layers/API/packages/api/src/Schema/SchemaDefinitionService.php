<?php

declare(strict_types=1);

namespace PoP\API\Schema;

use Exception;
use PoP\API\Cache\CacheTypes;
use PoP\API\ComponentConfiguration;
use PoP\API\ObjectModels\SchemaDefinition\DirectiveSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\EnumTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\GlobalRootObjectTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\InputObjectTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\InterfaceTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\ObjectTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\RootObjectTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\ScalarTypeSchemaDefinitionProvider;
use PoP\API\ObjectModels\SchemaDefinition\SchemaDefinitionProviderInterface;
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
            $rootObjectTypeResolver = $this->getRootTypeResolver();
            $schemaDefinition = [
                SchemaDefinition::QUERY_TYPE => $rootObjectTypeResolver->getMaybeNamespacedTypeName(),
                SchemaDefinition::TYPES => [],
                SchemaDefinition::DIRECTIVES => [],
                SchemaDefinition::GLOBAL_DIRECTIVES => [],
                SchemaDefinition::GLOBAL_FIELDS => [],
                SchemaDefinition::GLOBAL_CONNECTIONS => [],
            ];

            /**
             * Starting from the Root TypeResolver, iterate and get the
             * SchemaDefinition for all TypeResolvers and DirectiveResolvers
             * accessed in the schema
             */
            $processedTypeAndDirectiveResolverClasses = [];
            $accessedTypeAndDirectiveResolvers = [];
            /** @var array<TypeResolverInterface|DirectiveResolverInterface> */
            $pendingTypeOrDirectiveResolvers = $this->getRootObjectTypeResolvers();
            while (!empty($pendingTypeOrDirectiveResolvers)) {
                /** @var array $pendingTypeOrDirectiveResolvers */
                $typeOrDirectiveResolver = array_pop($pendingTypeOrDirectiveResolvers);
                $processedTypeAndDirectiveResolverClasses[] = $typeOrDirectiveResolver::class;                
                if ($typeOrDirectiveResolver instanceof TypeResolverInterface) {
                    /** @var TypeResolverInterface */
                    $typeResolver = $typeOrDirectiveResolver;
                    $accessedTypeAndDirectiveResolvers = $this->addTypeSchemaDefinition(
                        $schemaDefinition,
                        $typeResolver,
                    );
                } else {
                    /** @var DirectiveResolverInterface */
                    $directiveResolver = $typeOrDirectiveResolver;
                    $accessedTypeAndDirectiveResolvers = $this->addDirectiveSchemaDefinition(
                        $schemaDefinition,
                        $directiveResolver,
                    );
                }

                // Add further accessed TypeResolvers and DirectiveResolvers to the stack and keep iterating
                foreach ($accessedTypeAndDirectiveResolvers as $accessedTypeOrDirectiveResolver) {
                    if (in_array($accessedTypeOrDirectiveResolver::class, $processedTypeAndDirectiveResolverClasses)) {
                        continue;
                    }
                    $pendingTypeOrDirectiveResolvers[] = $accessedTypeOrDirectiveResolver;
                }
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

    /**
     * @return array<TypeResolverInterface|DirectiveResolverInterface> Accessed Type and Directive Resolvers
     */
    private function addTypeSchemaDefinition(array &$schemaDefinition, TypeResolverInterface $typeResolver): array
    {
        $schemaDefinitionProvider = $this->getTypeResolverSchemaDefinitionProvider($typeResolver);
        $type = $schemaDefinitionProvider->getType();
        $typeName = $typeResolver->getMaybeNamespacedTypeName();
        $typeSchemaDefinition = $schemaDefinitionProvider->getSchemaDefinition();
        /**
         * The RootObject has the special role of also calculating the
         * global fields, connections and directives
         */
        if (in_array($typeResolver, $this->getRootObjectTypeResolvers())) {
            $schemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES] = array_merge(
                $schemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES],
                $typeSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES]
            );
            $schemaDefinition[SchemaDefinition::GLOBAL_FIELDS] = array_merge(
                $schemaDefinition[SchemaDefinition::GLOBAL_FIELDS],
                $typeSchemaDefinition[SchemaDefinition::GLOBAL_FIELDS]
            );
            $schemaDefinition[SchemaDefinition::GLOBAL_CONNECTIONS] = array_merge(
                $schemaDefinition[SchemaDefinition::GLOBAL_CONNECTIONS],
                $typeSchemaDefinition[SchemaDefinition::GLOBAL_CONNECTIONS]
            );
            unset($typeSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES]);
            unset($typeSchemaDefinition[SchemaDefinition::GLOBAL_FIELDS]);
            unset($typeSchemaDefinition[SchemaDefinition::GLOBAL_CONNECTIONS]);
        }
        $schemaDefinition[SchemaDefinition::TYPES][$type][$typeName] = $typeSchemaDefinition;
        return [];
    }

    /**
     * @return array<TypeResolverInterface|DirectiveResolverInterface> Accessed Type and Directive Resolvers
     */
    private function addDirectiveSchemaDefinition(array &$schemaDefinition, DirectiveResolverInterface $directiveResolver): array
    {
        $schemaDefinitionProvider = new DirectiveSchemaDefinitionProvider($directiveResolver);
        $directiveName = $directiveResolver->getDirectiveName();
        $directiveSchemaDefinition = $schemaDefinitionProvider->getSchemaDefinition();
        $schemaDefinition[SchemaDefinition::DIRECTIVES][$directiveName] = $directiveSchemaDefinition;
        return [];
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
