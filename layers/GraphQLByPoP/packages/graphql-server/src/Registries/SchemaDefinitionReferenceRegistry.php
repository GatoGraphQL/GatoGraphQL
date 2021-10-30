<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Registries;

use Exception;
use GraphQLByPoP\GraphQLQuery\ComponentConfiguration as GraphQLQueryComponentConfiguration;
use GraphQLByPoP\GraphQLQuery\Schema\SchemaElements;
use GraphQLByPoP\GraphQLServer\Cache\CacheTypes;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use PoP\API\Schema\SchemaDefinitionHelpers as APISchemaDefinitionHelpers;
use PoP\API\Schema\SchemaDefinitionServiceInterface;
use PoP\API\Schema\TypeKinds;
use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Cache\CacheUtils;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaDefinitionReferenceRegistry implements SchemaDefinitionReferenceRegistryInterface
{
    use BasicServiceTrait;

    /**
     * @var array<string, mixed>
     */
    protected ?array $fullSchemaDefinitionForGraphQL = null;
    /**
     * @var array<string, SchemaDefinitionReferenceObjectInterface>
     */
    protected array $fullSchemaDefinitionReferenceDictionary = [];

    /**
     * Cannot autowire because its calling `getNamespace`
     * on services.yaml produces an exception of PHP properties not initialized
     * in its depended services.
     */
    private ?PersistentCacheInterface $persistentCache = null;

    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;
    private ?GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;

    final public function setSchemaDefinitionService(SchemaDefinitionServiceInterface $schemaDefinitionService): void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    final protected function getSchemaDefinitionService(): SchemaDefinitionServiceInterface
    {
        return $this->schemaDefinitionService ??= $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
    }
    final public function setGraphQLSchemaDefinitionService(GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService): void
    {
        $this->graphQLSchemaDefinitionService = $graphQLSchemaDefinitionService;
    }
    final protected function getGraphQLSchemaDefinitionService(): GraphQLSchemaDefinitionServiceInterface
    {
        return $this->graphQLSchemaDefinitionService ??= $this->instanceManager->getInstance(GraphQLSchemaDefinitionServiceInterface::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }

    final public function getPersistentCache(): PersistentCacheInterface
    {
        $this->persistentCache ??= PersistentCacheFacade::getInstance();
        return $this->persistentCache;
    }

    public function &getFullSchemaDefinitionForGraphQL(): array
    {
        if ($this->fullSchemaDefinitionForGraphQL === null) {
            $this->fullSchemaDefinitionForGraphQL = $this->doGetGraphQLSchemaDefinition();
        }

        return $this->fullSchemaDefinitionForGraphQL;
    }

    /**
     * It can store the value in the cache.
     *
     * Use cache with care: If the schema is dynamic, it should not be cached!
     *
     *   Public schema: can cache
     *   Private schema: cannot cache
     */
    private function &doGetGraphQLSchemaDefinition(): array
    {
        // Attempt to retrieve from the cache, if enabled
        if ($useCache = APIComponentConfiguration::useSchemaDefinitionCache()) {
            // Use different caches for the normal and namespaced schemas,
            // or it throws exception if switching without deleting the cache (eg: when passing ?use_namespace=1)
            $vars = ApplicationState::getVars();
            $cacheType = CacheTypes::GRAPHQL_SCHEMA_DEFINITION;
            $cacheKeyComponents = array_merge(
                CacheUtils::getSchemaCacheKeyComponents(),
                [
                    'edit-schema' => isset($vars['edit-schema']) && $vars['edit-schema'],
                ]
            );
            // For the persistentCache, use a hash to remove invalid characters (such as "()")
            $cacheKey = hash('md5', json_encode($cacheKeyComponents));

            $persistentCache = $this->getPersistentCache();
            if ($persistentCache->hasCache($cacheKey, $cacheType)) {
                $this->fullSchemaDefinitionForGraphQL = $persistentCache->getCache($cacheKey, $cacheType);
            }
        }

        // If either not using cache, or using but the value had not been cached, then calculate the value
        if ($this->fullSchemaDefinitionForGraphQL === null) {
            // Get the schema definitions
            $this->fullSchemaDefinitionForGraphQL = $this->getSchemaDefinitionService()->getFullSchemaDefinition();

            // Convert the schema from PoP's format to what GraphQL needs to work with
            $this->prepareSchemaDefinitionForGraphQL();

            // Store in the cache
            if ($useCache) {
                $persistentCache->storeCache($cacheKey, $cacheType, $this->fullSchemaDefinitionForGraphQL);
            }
        }

        return $this->fullSchemaDefinitionForGraphQL;
    }

    protected function prepareSchemaDefinitionForGraphQL(): void
    {
        $vars = ApplicationState::getVars();
        $enableNestedMutations = $vars['nested-mutations-enabled'];
        $exposeSchemaIntrospectionFieldInSchema = ComponentConfiguration::exposeSchemaIntrospectionFieldInSchema();
        $exposeGlobalFieldsInGraphQLSchema = ComponentConfiguration::exposeGlobalFieldsInGraphQLSchema();

        $rootObjectTypeResolver = $this->getGraphQLSchemaDefinitionService()->getSchemaRootObjectTypeResolver();
        $rootTypeName = $rootObjectTypeResolver->getMaybeNamespacedTypeName();
        $queryRootTypeName = null;
        $addConnectionFromRootToQueryRootAndMutationRoot = ComponentConfiguration::addConnectionFromRootToQueryRootAndMutationRoot();
        if (!$enableNestedMutations || $addConnectionFromRootToQueryRootAndMutationRoot) {
            $queryRootTypeResolver = $this->getGraphQLSchemaDefinitionService()->getSchemaQueryRootObjectTypeResolver();
            $queryRootTypeName = $queryRootTypeResolver->getMaybeNamespacedTypeName();
            // Additionally append the QueryRoot and MutationRoot to the schema
            if ($addConnectionFromRootToQueryRootAndMutationRoot) {
                // Remove the fields connecting from Root to QueryRoot and MutationRoot
                unset($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$rootTypeName][SchemaDefinition::FIELDS]['queryRoot']);
                unset($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$rootTypeName][SchemaDefinition::FIELDS]['mutationRoot']);
            }
        }

        if ($exposeGlobalFieldsInGraphQLSchema) {
            /**
             * Remove the introspection fields that must not be added to the schema:
             * [GraphQL spec] Field "__typename" from all types.
             * > This field is implicit and does not appear in the fields list in any defined type.
             * @see http://spec.graphql.org/draft/#sel-FAJVHCBvBBhC4iC
             */
            unset($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_FIELDS]['__typename']);
        } else {
            unset($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_FIELDS]);
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
            unset($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$rootTypeName][SchemaDefinition::FIELDS]['__type']);
            unset($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$rootTypeName][SchemaDefinition::FIELDS]['__schema']);
            if ($queryRootTypeName !== null) {
                unset($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$queryRootTypeName][SchemaDefinition::FIELDS]['__type']);
                unset($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$queryRootTypeName][SchemaDefinition::FIELDS]['__schema']);
            }
        }

        // Remove unneeded data
        if (!ComponentConfiguration::exposeSelfFieldInGraphQLSchema()) {
            /**
             * Check if to remove the "self" field everywhere, or if to keep it just for the Root type
             */
            $keepSelfFieldForRootType = ComponentConfiguration::exposeSelfFieldForRootTypeInGraphQLSchema();
            foreach ($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES] as $typeKind => $typeSchemaDefinitions) {
                foreach (array_keys($typeSchemaDefinitions) as $typeName) {
                    if (!$keepSelfFieldForRootType || ($typeName !== $rootTypeName && ($enableNestedMutations || $typeName !== $queryRootTypeName))) {
                        unset($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][$typeKind][$typeName][SchemaDefinition::FIELDS]['self']);
                    }
                }
            }
        }
        if (!ComponentConfiguration::addFullSchemaFieldToGraphQLSchema()) {
            unset($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$rootTypeName][SchemaDefinition::FIELDS]['fullSchema']);
            if ($queryRootTypeName !== null) {
                unset($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT][$queryRootTypeName][SchemaDefinition::FIELDS]['fullSchema']);
            }
        }

        // Maybe append the field/directive's version to its description, since this field is missing in GraphQL
        $addVersionToGraphQLSchemaFieldDescription = ComponentConfiguration::addVersionToGraphQLSchemaFieldDescription();
        // When doing nested mutations, differentiate mutating fields by adding label "[Mutation]" in the description
        $addMutationLabelToSchemaFieldDescription = $enableNestedMutations;
        // Maybe add param "nestedUnder" on the schema for each directive
        $enableComposableDirectives = GraphQLQueryComponentConfiguration::enableComposableDirectives();

        // Modify the schema definitions
        // 1. Global fields and directives
        if (
            ($addVersionToGraphQLSchemaFieldDescription || $addMutationLabelToSchemaFieldDescription)
            && $exposeGlobalFieldsInGraphQLSchema
        ) {
            foreach (array_keys($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_FIELDS]) as $fieldName) {
                $itemPath = [
                    SchemaDefinition::GLOBAL_FIELDS,
                    $fieldName
                ];
                if ($addVersionToGraphQLSchemaFieldDescription) {
                    $this->addVersionToGraphQLSchemaFieldDescription($itemPath);
                }
                if ($addMutationLabelToSchemaFieldDescription) {
                    $this->addMutationLabelToSchemaFieldDescription($itemPath);
                }
            }
        }
        // Remove all directives of types other than "Query", "Schema" and, maybe "Indexing"
        $supportedDirectiveTypes = [
            DirectiveTypes::SCHEMA,
            DirectiveTypes::QUERY,
        ];
        if ($enableComposableDirectives) {
            $supportedDirectiveTypes [] = DirectiveTypes::INDEXING;
        }
        $directivesNamesToRemove = [];
        foreach (array_keys($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_DIRECTIVES]) as $directiveName) {
            if (!in_array($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_DIRECTIVES][$directiveName][SchemaDefinition::DIRECTIVE_TYPE], $supportedDirectiveTypes)) {
                $directivesNamesToRemove[] = $directiveName;
            }
        }
        foreach ($directivesNamesToRemove as $directiveName) {
            unset($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_DIRECTIVES][$directiveName]);
        }
        // Add the directives
        foreach (array_keys($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::GLOBAL_DIRECTIVES]) as $directiveName) {
            $itemPath = [
                SchemaDefinition::GLOBAL_DIRECTIVES,
                $directiveName
            ];
            if ($enableComposableDirectives) {
                $this->addNestedDirectiveDataToSchemaDirectiveArgs($itemPath);
            }
            if ($addVersionToGraphQLSchemaFieldDescription) {
                $this->addVersionToGraphQLSchemaFieldDescription($itemPath);
            }
            $this->maybeAddTypeToSchemaDirectiveDescription($itemPath);
        }
        // 2. Each type's fields and directives
        if ($addVersionToGraphQLSchemaFieldDescription || $addMutationLabelToSchemaFieldDescription/* || $enableComposableDirectives*/) {
            foreach ($this->fullSchemaDefinitionForGraphQL[SchemaDefinition::TYPES][TypeKinds::OBJECT] as $typeName => $typeSchemaDefinition) {
                foreach (array_keys($typeSchemaDefinition[SchemaDefinition::FIELDS]) as $fieldName) {
                    $itemPath = [
                        SchemaDefinition::TYPES,
                        TypeKinds::OBJECT,
                        $typeName,
                        SchemaDefinition::FIELDS,
                        $fieldName
                    ];
                    if ($addVersionToGraphQLSchemaFieldDescription) {
                        $this->addVersionToGraphQLSchemaFieldDescription($itemPath);
                    }
                    if ($addMutationLabelToSchemaFieldDescription) {
                        $this->addMutationLabelToSchemaFieldDescription($itemPath);
                    }
                }
            }
        }

        // Sort the elements in the schema alphabetically (if not already sorted!)
        if (
            !APIComponentConfiguration::sortFullSchemaAlphabetically()
            && ComponentConfiguration::sortGraphQLSchemaAlphabetically()
        ) {
            $this->getSchemaDefinitionService()->sortFullSchemaAlphabetically($this->fullSchemaDefinitionForGraphQL);
        }
    }

    /**
     * When doing /?edit_schema=true, "Schema" type directives will also be added the FIELD location,
     * so that they show up in GraphiQL and can be added to a persisted query
     * When that happens, append '("Schema" type directive)' to the directive's description
     */
    protected function maybeAddTypeToSchemaDirectiveDescription(array $directiveSchemaDefinitionPath): void
    {
        $vars = ApplicationState::getVars();
        if (isset($vars['edit-schema']) && $vars['edit-schema']) {
            $directiveSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinitionForGraphQL, $directiveSchemaDefinitionPath);
            if ($directiveSchemaDefinition[SchemaDefinition::DIRECTIVE_TYPE] == DirectiveTypes::SCHEMA) {
                $directiveSchemaDefinition[SchemaDefinition::DESCRIPTION] = sprintf(
                    $this->getTranslationAPI()->__('%s %s', 'graphql-server'),
                    sprintf(
                        '_%s_', // Make it italic using markdown
                        $this->getTranslationAPI()->__('("Schema" type directive)', 'graphql-server')
                    ),
                    $directiveSchemaDefinition[SchemaDefinition::DESCRIPTION]
                );
            }
        }
    }

    /**
     * Append the field or directive's version to its description
     */
    protected function addVersionToGraphQLSchemaFieldDescription(array $fieldOrDirectiveSchemaDefinitionPath): void
    {
        $fieldOrDirectiveSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinitionForGraphQL, $fieldOrDirectiveSchemaDefinitionPath);
        if ($schemaFieldVersion = $fieldOrDirectiveSchemaDefinition[SchemaDefinition::VERSION] ?? null) {
            $fieldOrDirectiveSchemaDefinition[SchemaDefinition::DESCRIPTION] .= sprintf(
                sprintf(
                    $this->getTranslationAPI()->__(' _%s_', 'graphql-server'), // Make it italic using markdown
                    $this->getTranslationAPI()->__('(Version: %s)', 'graphql-server')
                ),
                $schemaFieldVersion
            );
        }
    }

    /**
     * Append param "nestedUnder" to the directive
     */
    protected function addNestedDirectiveDataToSchemaDirectiveArgs(array $directiveSchemaDefinitionPath): void
    {
        $directiveSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinitionForGraphQL, $directiveSchemaDefinitionPath);
        $directiveSchemaDefinition[SchemaDefinition::ARGS] ??= [];
        $directiveArgSchemaDefinition = [
            SchemaDefinition::NAME => SchemaElements::DIRECTIVE_PARAM_NESTED_UNDER,
            SchemaDefinition::TYPE_RESOLVER => $this->getIntScalarTypeResolver(),
            SchemaDefinition::DESCRIPTION => $this->getTranslationAPI()->__('Nest the directive under another one, indicated as a relative position from this one (a negative int)', 'graphql-server'),
        ];
        APISchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($directiveArgSchemaDefinition);
        $directiveSchemaDefinition[SchemaDefinition::ARGS][] = $directiveArgSchemaDefinition;
    }

    /**
     * Append the "Mutation" label to the field's description
     */
    protected function addMutationLabelToSchemaFieldDescription(array $fieldSchemaDefinitionPath): void
    {
        $fieldSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinitionForGraphQL, $fieldSchemaDefinitionPath);
        if ($fieldSchemaDefinition[SchemaDefinition::FIELD_IS_MUTATION] ?? null) {
            $fieldSchemaDefinition[SchemaDefinition::DESCRIPTION] = sprintf(
                $this->getTranslationAPI()->__('[Mutation] %s', 'graphql-server'),
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
            throw new Exception(sprintf(
                $this->getTranslationAPI()->__('A Schema Definition Reference Object with id \'%s\s has already been registered', 'graphql-server'),
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
