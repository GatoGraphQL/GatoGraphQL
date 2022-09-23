<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Registries;

use GraphQLByPoP\GraphQLServer\Cache\CacheTypes;
use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Exception\SchemaReferenceException;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\Cache\CacheUtils;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\ModuleConfiguration as GraphQLParserModuleConfiguration;
use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;
use PoPAPI\API\Module as APIModule;
use PoPAPI\API\ModuleConfiguration as APIModuleConfiguration;
use PoPAPI\API\Schema\SchemaDefinitionServiceInterface;
use PoPAPI\API\Schema\TypeKinds;

class SchemaDefinitionReferenceRegistry implements SchemaDefinitionReferenceRegistryInterface
{
    use BasicServiceTrait;

    /**
     * @var array<string,mixed>|null
     */
    protected ?array $fullSchemaDefinitionForGraphQL = null;
    /**
     * @var array<string,SchemaDefinitionReferenceObjectInterface>
     */
    protected array $fullSchemaDefinitionReferenceDictionary = [];

    private ?PersistentCacheInterface $persistentCache = null;
    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;
    private ?GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;

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
        /** @var PersistentCacheInterface */
        return $this->persistentCache ??= $this->instanceManager->getInstance(PersistentCacheInterface::class);
    }
    final public function setSchemaDefinitionService(SchemaDefinitionServiceInterface $schemaDefinitionService): void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    final protected function getSchemaDefinitionService(): SchemaDefinitionServiceInterface
    {
        /** @var SchemaDefinitionServiceInterface */
        return $this->schemaDefinitionService ??= $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
    }
    final public function setGraphQLSchemaDefinitionService(GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService): void
    {
        $this->graphQLSchemaDefinitionService = $graphQLSchemaDefinitionService;
    }
    final protected function getGraphQLSchemaDefinitionService(): GraphQLSchemaDefinitionServiceInterface
    {
        /** @var GraphQLSchemaDefinitionServiceInterface */
        return $this->graphQLSchemaDefinitionService ??= $this->instanceManager->getInstance(GraphQLSchemaDefinitionServiceInterface::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        /** @var IntScalarTypeResolver */
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }

    /**
     * @return array<string,mixed>
     */
    public function &getFullSchemaDefinitionForGraphQL(): array
    {
        if ($this->fullSchemaDefinitionForGraphQL === null) {
            $this->fullSchemaDefinitionForGraphQL = $this->doGetGraphQLSchemaDefinition();
        }

        /** @var array<string,mixed> */
        return $this->fullSchemaDefinitionForGraphQL;
    }

    /**
     * It can store the value in the cache.
     *
     * Use cache with care: If the schema is dynamic, it should not be cached!
     *
     *   Public schema: can cache
     *   Private schema: cannot cache
     *
     * @return array<string,mixed>
     */
    private function &doGetGraphQLSchemaDefinition(): array
    {
        /**
         * @var array<string,mixed>|null
         */
        $fullSchemaDefinitionForGraphQL = null;

        // Attempt to retrieve from the cache, if enabled
        /** @var APIModuleConfiguration */
        $moduleConfiguration = App::getModule(APIModule::class)->getConfiguration();
        if ($useCache = $moduleConfiguration->useSchemaDefinitionCache()) {
            // Use different caches for the normal and namespaced schemas,
            // or it throws exception if switching without deleting the cache (eg: when passing ?use_namespace=1)
            $cacheType = CacheTypes::GRAPHQL_SCHEMA_DEFINITION;
            $cacheKeyElements = array_merge(
                CacheUtils::getSchemaCacheKeyElements(),
                [
                    'edit-schema' => App::getState('edit-schema'),
                ]
            );
            // For the persistentCache, use a hash to remove invalid characters (such as "()")
            $cacheKey = hash('md5', (string)json_encode($cacheKeyElements));

            $persistentCache = $this->getPersistentCache();
            if ($persistentCache->hasCache($cacheKey, $cacheType)) {
                $fullSchemaDefinitionForGraphQL = $persistentCache->getCache($cacheKey, $cacheType);
            }
        }

        // If either not using cache, or using but the value had not been cached, then calculate the value
        if ($fullSchemaDefinitionForGraphQL === null) {
            // Get the schema definitions
            $fullSchemaDefinitionForGraphQL = $this->getSchemaDefinitionService()->getFullSchemaDefinition();

            // Convert the schema from PoP's format to what GraphQL needs to work with
            $this->prepareSchemaDefinitionForGraphQL($fullSchemaDefinitionForGraphQL);

            // Store in the cache
            if ($useCache) {
                $persistentCache->storeCache($cacheKey, $cacheType, $fullSchemaDefinitionForGraphQL);
            }
        }

        /**
         * @var array<string,mixed>
         */
        return $fullSchemaDefinitionForGraphQL;
    }

    /**
     * @param array<string,mixed> $fullSchemaDefinitionForGraphQL
     */
    private function prepareSchemaDefinitionForGraphQL(array &$fullSchemaDefinitionForGraphQL): void
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $enableNestedMutations = $moduleConfiguration->enableNestedMutations();
        $exposeSchemaIntrospectionFieldInSchema = $moduleConfiguration->exposeSchemaIntrospectionFieldInSchema();
        $exposeGlobalFieldsInGraphQLSchema = $moduleConfiguration->exposeGlobalFieldsInGraphQLSchema();

        $rootObjectTypeResolver = $this->getGraphQLSchemaDefinitionService()->getSchemaRootObjectTypeResolver();
        $rootTypeName = $rootObjectTypeResolver->getMaybeNamespacedTypeName();
        $queryRootTypeName = null;
        $addConnectionFromRootToQueryRootAndMutationRoot = $moduleConfiguration->addConnectionFromRootToQueryRootAndMutationRoot();
        if (!$enableNestedMutations || $addConnectionFromRootToQueryRootAndMutationRoot) {
            $queryRootTypeResolver = $this->getGraphQLSchemaDefinitionService()->getSchemaQueryRootObjectTypeResolver();
            $queryRootTypeName = $queryRootTypeResolver->getMaybeNamespacedTypeName();
            // Remove the fields connecting from Root to QueryRoot and MutationRoot
            unset($fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$rootTypeName][SchemaDefinition::FIELDS]['queryRoot']);
            unset($fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$rootTypeName][SchemaDefinition::FIELDS]['mutationRoot']);
        }

        if ($exposeGlobalFieldsInGraphQLSchema) {
            /**
             * Remove the introspection fields that must not be added to the schema:
             * [GraphQL spec] Field "__typename" from all types.
             * > This field is implicit and does not appear in the fields list in any defined type.
             * @see http://spec.graphql.org/draft/#sel-FAJVHCBvBBhC4iC
             */
            unset($fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_FIELDS]['__typename']);
        } else {
            unset($fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_FIELDS]);
        }

        /**
         * These fields can be exposed in the schema when configuring ACL,
         * as to remove user access to "__schema" to disable introspection
         */
        if (!$exposeSchemaIntrospectionFieldInSchema) {
            /**
             * Remove the introspection fields that must not be added to the schema:
             * [GraphQL spec] Fields "__schema" and "__type" from the query type.
             * > These fields are implicit and do not appear in the fields list in the root type of the query operation.
             * @see http://spec.graphql.org/draft/#sel-FAJXHABcBlB6rF
             */
            unset($fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$rootTypeName][SchemaDefinition::FIELDS]['__type']);
            unset($fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$rootTypeName][SchemaDefinition::FIELDS]['__schema']);
            if ($queryRootTypeName !== null) {
                unset($fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$queryRootTypeName][SchemaDefinition::FIELDS]['__type']);
                unset($fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$queryRootTypeName][SchemaDefinition::FIELDS]['__schema']);
            }
        }

        /**
         * Remove unneeded data
         *
         * @var ComponentModelModuleConfiguration
         */
        $componentModelModuleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        if (!$componentModelModuleConfiguration->enableSelfField()) {
            /** @var string $typeKind */
            foreach ($fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES] as $typeKind => $typeSchemaDefinitions) {
                /** @var string $typeName */
                foreach (array_keys($typeSchemaDefinitions) as $typeName) {
                    unset($fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][$typeKind][$typeName][SchemaDefinition::FIELDS]['self']);
                }
            }
        }

        // Maybe append the field/directive's version to its description, since this field is missing in GraphQL
        $addVersionToGraphQLSchemaFieldDescription = $moduleConfiguration->addVersionToGraphQLSchemaFieldDescription();
        // When doing nested mutations, differentiate mutating fields by adding label "[Mutation]" in the description
        $addMutationLabelToSchemaFieldDescription = $enableNestedMutations;
        /** @var GraphQLParserModuleConfiguration */
        $graphQLParserModuleConfiguration = App::getModule(GraphQLParserModule::class)->getConfiguration();
        $enableComposableDirectives = $graphQLParserModuleConfiguration->enableComposableDirectives();

        // Modify the schema definitions
        // 1. Global fields and directives
        if (
            ($addVersionToGraphQLSchemaFieldDescription || $addMutationLabelToSchemaFieldDescription)
            && $exposeGlobalFieldsInGraphQLSchema
        ) {
            /** @var string $fieldName */
            foreach (array_keys($fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_FIELDS]) as $fieldName) {
                /** @var string[] */
                $itemPath = [
                    SchemaDefinition::GLOBAL_FIELDS,
                    $fieldName
                ];
                if ($addVersionToGraphQLSchemaFieldDescription) {
                    $this->addVersionToGraphQLSchemaFieldDescription($fullSchemaDefinitionForGraphQL, $itemPath);
                }
                if ($addMutationLabelToSchemaFieldDescription) {
                    $this->addMutationLabelToSchemaFieldDescription($fullSchemaDefinitionForGraphQL, $itemPath);
                }
            }
        }
        // Remove all directives of types other than "Query", "Schema" and, maybe "Indexing"
        $supportedDirectiveKinds = [
            DirectiveKinds::SCHEMA,
            DirectiveKinds::QUERY,
        ];
        if ($enableComposableDirectives) {
            $supportedDirectiveKinds [] = DirectiveKinds::INDEXING;
        }
        $directivesNamesToRemove = [];
        /** @var string $directiveName */
        foreach (array_keys($fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_DIRECTIVES]) as $directiveName) {
            if (!in_array($fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_DIRECTIVES][$directiveName][SchemaDefinition::DIRECTIVE_KIND], $supportedDirectiveKinds)) {
                $directivesNamesToRemove[] = $directiveName;
            }
        }
        foreach ($directivesNamesToRemove as $directiveName) {
            unset($fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_DIRECTIVES][$directiveName]);
        }
        // Add the directives
        /** @var string $directiveName */
        foreach (array_keys($fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_DIRECTIVES]) as $directiveName) {
            /** @var string[] */
            $itemPath = [
                SchemaDefinition::GLOBAL_DIRECTIVES,
                $directiveName
            ];
            if ($addVersionToGraphQLSchemaFieldDescription) {
                $this->addVersionToGraphQLSchemaFieldDescription($fullSchemaDefinitionForGraphQL, $itemPath);
            }
            $this->maybeAddTypeToSchemaDirectiveDescription($fullSchemaDefinitionForGraphQL, $itemPath);
        }
        // 2. Each type's fields and directives
        if ($addVersionToGraphQLSchemaFieldDescription || $addMutationLabelToSchemaFieldDescription) {
            /** @var string $typeName */
            foreach ($fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT] as $typeName => $typeSchemaDefinition) {
                /** @var string $fieldName */
                foreach (array_keys($typeSchemaDefinition[SchemaDefinition::FIELDS]) as $fieldName) {
                    /** @var string[] */
                    $itemPath = [
                        SchemaDefinition::TYPES,
                        TypeKinds::OBJECT,
                        $typeName,
                        SchemaDefinition::FIELDS,
                        $fieldName
                    ];
                    if ($addVersionToGraphQLSchemaFieldDescription) {
                        $this->addVersionToGraphQLSchemaFieldDescription($fullSchemaDefinitionForGraphQL, $itemPath);
                    }
                    if ($addMutationLabelToSchemaFieldDescription) {
                        $this->addMutationLabelToSchemaFieldDescription($fullSchemaDefinitionForGraphQL, $itemPath);
                    }
                }
            }
        }

        // Sort the elements in the schema alphabetically (if not already sorted!)
        /** @var APIModuleConfiguration */
        $apiModuleConfiguration = App::getModule(APIModule::class)->getConfiguration();
        if (
            !$apiModuleConfiguration->sortFullSchemaAlphabetically()
            && $moduleConfiguration->sortGraphQLSchemaAlphabetically()
        ) {
            $this->getSchemaDefinitionService()->sortFullSchemaAlphabetically($fullSchemaDefinitionForGraphQL);
        }
    }

    /**
     * When doing /?edit_schema=true, "Schema" type directives will also be added the FIELD location,
     * so that they show up in GraphiQL and can be added to a persisted query
     * When that happens, append '("Schema" type directive)' to the directive's description
     *
     * @param array<string,mixed> $fullSchemaDefinitionForGraphQL
     * @param string[] $directiveSchemaDefinitionPath
     */
    private function maybeAddTypeToSchemaDirectiveDescription(
        array &$fullSchemaDefinitionForGraphQL,
        array $directiveSchemaDefinitionPath,
    ): void {
        if (App::getState('edit-schema')) {
            $directiveSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($fullSchemaDefinitionForGraphQL, $directiveSchemaDefinitionPath);
            if ($directiveSchemaDefinition[SchemaDefinition::DIRECTIVE_KIND] === DirectiveKinds::SCHEMA) {
                $directiveSchemaDefinition[SchemaDefinition::DESCRIPTION] = sprintf(
                    $this->__('%s %s', 'graphql-server'),
                    sprintf(
                        '_%s_', // Make it italic using markdown
                        $this->__('("Schema" type directive)', 'graphql-server')
                    ),
                    $directiveSchemaDefinition[SchemaDefinition::DESCRIPTION]
                );
            }
        }
    }

    /**
     * Append the field or directive's version to its description
     *
     * @param array<string,mixed> $fullSchemaDefinitionForGraphQL
     * @param string[] $fieldOrDirectiveSchemaDefinitionPath
     */
    private function addVersionToGraphQLSchemaFieldDescription(
        array &$fullSchemaDefinitionForGraphQL,
        array $fieldOrDirectiveSchemaDefinitionPath,
    ): void {
        $fieldOrDirectiveSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($fullSchemaDefinitionForGraphQL, $fieldOrDirectiveSchemaDefinitionPath);
        if ($schemaFieldVersion = $fieldOrDirectiveSchemaDefinition[SchemaDefinition::VERSION] ?? null) {
            $fieldOrDirectiveSchemaDefinition[SchemaDefinition::DESCRIPTION] .= sprintf(
                sprintf(
                    $this->__(' _%s_', 'graphql-server'), // Make it italic using markdown
                    $this->__('(Version: %s)', 'graphql-server')
                ),
                $schemaFieldVersion
            );
        }
    }

    /**
     * Append the "Mutation" label to the field's description
     *
     * @param array<string,mixed> $fullSchemaDefinitionForGraphQL
     * @param string[] $fieldSchemaDefinitionPath
     */
    private function addMutationLabelToSchemaFieldDescription(
        array &$fullSchemaDefinitionForGraphQL,
        array $fieldSchemaDefinitionPath,
    ): void {
        $fieldSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($fullSchemaDefinitionForGraphQL, $fieldSchemaDefinitionPath);
        if ($fieldSchemaDefinition[SchemaDefinition::EXTENSIONS][SchemaDefinition::FIELD_IS_MUTATION]) {
            $fieldSchemaDefinition[SchemaDefinition::DESCRIPTION] = sprintf(
                $this->__('[Mutation] %s', 'graphql-server'),
                $fieldSchemaDefinition[SchemaDefinition::DESCRIPTION]
            );
        }
    }

    public function registerSchemaDefinitionReferenceObject(
        SchemaDefinitionReferenceObjectInterface $schemaDefinitionReferenceObject,
    ): string {
        $schemaDefinitionPath = $schemaDefinitionReferenceObject->getSchemaDefinitionPath();
        $schemaDefinitionReferenceObjectID = SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID($schemaDefinitionPath);
        if (isset($this->fullSchemaDefinitionReferenceDictionary[$schemaDefinitionReferenceObjectID])) {
            throw new SchemaReferenceException(sprintf(
                $this->__('A Schema Definition Reference Object with id \'%s\s has already been registered', 'graphql-server'),
                $schemaDefinitionReferenceObjectID
            ));
        }
        $this->fullSchemaDefinitionReferenceDictionary[$schemaDefinitionReferenceObjectID] = $schemaDefinitionReferenceObject;
        return $schemaDefinitionReferenceObjectID;
    }
    public function getSchemaDefinitionReferenceObject(
        string $schemaDefinitionReferenceObjectID
    ): ?SchemaDefinitionReferenceObjectInterface {
        return $this->fullSchemaDefinitionReferenceDictionary[$schemaDefinitionReferenceObjectID] ?? null;
    }
}
