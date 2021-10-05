<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Registries;

use GraphQLByPoP\GraphQLQuery\ComponentConfiguration as GraphQLQueryComponentConfiguration;
use GraphQLByPoP\GraphQLQuery\Schema\SchemaElements;
use GraphQLByPoP\GraphQLServer\Cache\CacheTypes;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractDynamicType;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionTypes as GraphQLServerSchemaDefinitionTypes;
use GraphQLByPoP\GraphQLServer\Schema\SchemaHelpers;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use PoP\API\Registries\SchemaDefinitionRegistryInterface;
use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionShapes;
use PoP\ComponentModel\Schema\SchemaDefinitionTypes;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Cache\CacheUtils;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaDefinitionReferenceRegistry implements SchemaDefinitionReferenceRegistryInterface
{
    /**
     * @var array<string, mixed>
     */
    protected ?array $fullSchemaDefinition = null;
    protected bool $isFullSchemaDefinitionLoaded = false;
    /**
     * @var array<string, AbstractSchemaDefinitionReferenceObject>
     */
    protected array $fullSchemaDefinitionReferenceDictionary = [];
    /**
     * @var AbstractDynamicType[]
     */
    protected array $dynamicTypes = [];

    /**
     * Cannot autowire because its calling `getNamespace`
     * on services.yaml produces an exception of PHP properties not initialized
     * in its depended services.
     */
    protected ?PersistentCacheInterface $persistentCache = null;

    protected TranslationAPIInterface $translationAPI;
    protected SchemaDefinitionServiceInterface $schemaDefinitionService;
    protected QueryRootObjectTypeResolver $queryRootObjectTypeResolver;
    protected SchemaDefinitionRegistryInterface $schemaDefinitionRegistry;
    protected GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService;

    #[Required]
    final public function autowireSchemaDefinitionReferenceRegistry(
        TranslationAPIInterface $translationAPI,
        SchemaDefinitionServiceInterface $schemaDefinitionService,
        QueryRootObjectTypeResolver $queryRootObjectTypeResolver,
        SchemaDefinitionRegistryInterface $schemaDefinitionRegistry,
        GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService,
    ): void {
        $this->translationAPI = $translationAPI;
        $this->schemaDefinitionService = $schemaDefinitionService;
        $this->queryRootObjectTypeResolver = $queryRootObjectTypeResolver;
        $this->schemaDefinitionRegistry = $schemaDefinitionRegistry;
        $this->graphQLSchemaDefinitionService = $graphQLSchemaDefinitionService;
    }

    final public function getPersistentCache(): PersistentCacheInterface
    {
        $this->persistentCache ??= PersistentCacheFacade::getInstance();
        return $this->persistentCache;
    }

    /**
     * It returns the full schema, expanded with all data required to satisfy
     * GraphQL's introspection fields (starting from "__schema")
     *
     * It can store the value in the cache.
     * Use cache with care: if the schema is dynamic, it should not be cached.
     * Public schema: can cache, Private schema: cannot cache.
     *
     * Return null if retrieving the schema data via field "fullSchema" failed
     */
    public function &getFullSchemaDefinition(): ?array
    {
        // Use a bool flag, because the fullSchemaDefinition can be null!
        if (!$this->isFullSchemaDefinitionLoaded) {
            $this->isFullSchemaDefinitionLoaded = true;

            // These are the configuration options to work with the "full schema"
            $fieldArgs = [
                'deep' => true,
                'shape' => SchemaDefinitionShapes::FLAT,
                'compressed' => true,
                'useTypeName' => true,
            ];

            // Attempt to retrieve from the cache, if enabled
            if ($useCache = APIComponentConfiguration::useSchemaDefinitionCache()) {
                // Use different caches for the normal and namespaced schemas,
                // or it throws exception if switching without deleting the cache (eg: when passing ?use_namespace=1)
                $vars = ApplicationState::getVars();
                $cacheType = CacheTypes::GRAPHQL_SCHEMA_DEFINITION;
                $cacheKeyComponents = array_merge(
                    $fieldArgs,
                    CacheUtils::getSchemaCacheKeyComponents(),
                    [
                        'edit-schema' => isset($vars['edit-schema']) && $vars['edit-schema'],
                    ]
                );
                // For the persistentCache, use a hash to remove invalid characters (such as "()")
                $cacheKey = hash('md5', json_encode($cacheKeyComponents));
            }
            if ($useCache) {
                if ($this->getPersistentCache()->hasCache($cacheKey, $cacheType)) {
                    $this->fullSchemaDefinition = $this->getPersistentCache()->getCache($cacheKey, $cacheType);
                }
            }

            // If either not using cache, or using but the value had not been cached, then calculate the value
            if ($this->fullSchemaDefinition === null) {
                // Get the schema definitions
                $this->fullSchemaDefinition = $this->schemaDefinitionRegistry->getSchemaDefinition($fieldArgs);

                // If the schemaDefinition is null, it failed generating it. Then do nothing
                if ($this->fullSchemaDefinition === null) {
                    return $this->fullSchemaDefinition;
                }

                // Convert the schema from PoP's format to what GraphQL needs to work with
                $this->prepareSchemaDefinitionForGraphQL();

                // Store in the cache
                if ($useCache) {
                    $this->getPersistentCache()->storeCache($cacheKey, $cacheType, $this->fullSchemaDefinition);
                }
            }
        }

        return $this->fullSchemaDefinition;
    }
    protected function prepareSchemaDefinitionForGraphQL(): void
    {
        $vars = ApplicationState::getVars();
        $enableNestedMutations = $vars['nested-mutations-enabled'];
        $exposeSchemaIntrospectionFieldInSchema = ComponentConfiguration::exposeSchemaIntrospectionFieldInSchema();

        $rootTypeSchemaKey = $this->graphQLSchemaDefinitionService->getRootTypeSchemaKey();
        $queryRootTypeSchemaKey = null;
        if (!$enableNestedMutations) {
            $queryRootTypeSchemaKey = $this->graphQLSchemaDefinitionService->getQueryRootTypeSchemaKey();
        } elseif (ComponentConfiguration::addConnectionFromRootToQueryRootAndMutationRoot()) {
            // Additionally append the QueryRoot and MutationRoot to the schema
            $queryRootTypeSchemaKey = $this->graphQLSchemaDefinitionService->getTypeResolverTypeSchemaKey($this->queryRootObjectTypeResolver);
            // Remove the fields connecting from Root to QueryRoot and MutationRoot
            unset($this->fullSchemaDefinition[SchemaDefinition::TYPES][$rootTypeSchemaKey][SchemaDefinition::CONNECTIONS]['queryRoot']);
            unset($this->fullSchemaDefinition[SchemaDefinition::TYPES][$rootTypeSchemaKey][SchemaDefinition::CONNECTIONS]['mutationRoot']);
        }

        /**
         * Remove the introspection fields that must not be added to the schema:
         * [GraphQL spec] Field "__typename" from all types.
         * > This field is implicit and does not appear in the fields list in any defined type.
         * @see http://spec.graphql.org/draft/#sel-FAJVHCBvBBhC4iC
         */
        unset($this->fullSchemaDefinition[SchemaDefinition::GLOBAL_FIELDS]['__typename']);

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
            unset($this->fullSchemaDefinition[SchemaDefinition::TYPES][$rootTypeSchemaKey][SchemaDefinition::CONNECTIONS]['__type']);
            unset($this->fullSchemaDefinition[SchemaDefinition::TYPES][$rootTypeSchemaKey][SchemaDefinition::CONNECTIONS]['__schema']);
            if ($queryRootTypeSchemaKey !== null) {
                unset($this->fullSchemaDefinition[SchemaDefinition::TYPES][$queryRootTypeSchemaKey][SchemaDefinition::CONNECTIONS]['__type']);
                unset($this->fullSchemaDefinition[SchemaDefinition::TYPES][$queryRootTypeSchemaKey][SchemaDefinition::CONNECTIONS]['__schema']);
            }
        }

        // Remove unneeded data
        if (!ComponentConfiguration::addGlobalFieldsToSchema()) {
            unset($this->fullSchemaDefinition[SchemaDefinition::GLOBAL_FIELDS]);
            unset($this->fullSchemaDefinition[SchemaDefinition::GLOBAL_CONNECTIONS]);
        }
        if (!ComponentConfiguration::exposeSelfFieldInSchema()) {
            /**
             * Check if to remove the "self" field everywhere, or if to keep it just for the Root type
             */
            $keepSelfFieldForRootType = ComponentConfiguration::addSelfFieldForRootTypeToSchema();
            foreach (array_keys($this->fullSchemaDefinition[SchemaDefinition::TYPES]) as $typeSchemaKey) {
                if (!$keepSelfFieldForRootType || ($typeSchemaKey != $rootTypeSchemaKey && ($enableNestedMutations || $typeSchemaKey != $queryRootTypeSchemaKey))) {
                    unset($this->fullSchemaDefinition[SchemaDefinition::TYPES][$typeSchemaKey][SchemaDefinition::CONNECTIONS]['self']);
                }
            }
        }
        if (!ComponentConfiguration::addFullSchemaFieldToSchema()) {
            unset($this->fullSchemaDefinition[SchemaDefinition::TYPES][$rootTypeSchemaKey][SchemaDefinition::FIELDS]['fullSchema']);
            if ($queryRootTypeSchemaKey !== null) {
                unset($this->fullSchemaDefinition[SchemaDefinition::TYPES][$queryRootTypeSchemaKey][SchemaDefinition::FIELDS]['fullSchema']);
            }
        }

        // Maybe append the field/directive's version to its description, since this field is missing in GraphQL
        $addVersionToSchemaFieldDescription = ComponentConfiguration::addVersionToSchemaFieldDescription();
        // When doing nested mutations, differentiate mutating fields by adding label "[Mutation]" in the description
        $addMutationLabelToSchemaFieldDescription = $enableNestedMutations;
        // Maybe add param "nestedUnder" on the schema for each directive
        $enableComposableDirectives = GraphQLQueryComponentConfiguration::enableComposableDirectives();

        // Convert the field type from its internal representation (eg: "array:Post") to the GraphQL standard representation (eg: "[Post]")
        // 1. Global fields, connections and directives
        if (ComponentConfiguration::addGlobalFieldsToSchema()) {
            foreach (array_keys($this->fullSchemaDefinition[SchemaDefinition::GLOBAL_FIELDS]) as $fieldName) {
                $itemPath = [
                    SchemaDefinition::GLOBAL_FIELDS,
                    $fieldName
                ];
                $this->introduceSDLNotationToFieldSchemaDefinition($itemPath);
                if ($addVersionToSchemaFieldDescription) {
                    $this->addVersionToSchemaFieldDescription($itemPath);
                }
                if ($addMutationLabelToSchemaFieldDescription) {
                    $this->addMutationLabelToSchemaFieldDescription($itemPath);
                }
            }
            foreach (array_keys($this->fullSchemaDefinition[SchemaDefinition::GLOBAL_CONNECTIONS]) as $connectionName) {
                $itemPath = [
                    SchemaDefinition::GLOBAL_CONNECTIONS,
                    $connectionName
                ];
                $this->introduceSDLNotationToFieldSchemaDefinition($itemPath);
                if ($addVersionToSchemaFieldDescription) {
                    $this->addVersionToSchemaFieldDescription($itemPath);
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
        foreach (array_keys($this->fullSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES]) as $directiveName) {
            if (!in_array($this->fullSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES][$directiveName][SchemaDefinition::DIRECTIVE_TYPE], $supportedDirectiveTypes)) {
                $directivesNamesToRemove[] = $directiveName;
            }
        }
        foreach ($directivesNamesToRemove as $directiveName) {
            unset($this->fullSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES][$directiveName]);
        }
        // Add the directives
        foreach (array_keys($this->fullSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES]) as $directiveName) {
            $itemPath = [
                SchemaDefinition::GLOBAL_DIRECTIVES,
                $directiveName
            ];
            $this->introduceSDLNotationToFieldOrDirectiveArgs($itemPath);
            if ($enableComposableDirectives) {
                $this->addNestedDirectiveDataToSchemaDirectiveArgs($itemPath);
            }
            if ($addVersionToSchemaFieldDescription) {
                $this->addVersionToSchemaFieldDescription($itemPath);
            }
            $this->maybeAddTypeToSchemaDirectiveDescription($itemPath);
        }
        // 2. Each type's fields, connections and directives
        foreach ($this->fullSchemaDefinition[SchemaDefinition::TYPES] as $typeSchemaKey => $typeSchemaDefinition) {
            // No need for Union types
            if ($typeSchemaDefinition[SchemaDefinition::IS_UNION] ?? null) {
                continue;
            }
            foreach (array_keys($typeSchemaDefinition[SchemaDefinition::FIELDS]) as $fieldName) {
                $itemPath = [
                    SchemaDefinition::TYPES,
                    $typeSchemaKey,
                    SchemaDefinition::FIELDS,
                    $fieldName
                ];
                $this->introduceSDLNotationToFieldSchemaDefinition($itemPath);
                if ($addVersionToSchemaFieldDescription) {
                    $this->addVersionToSchemaFieldDescription($itemPath);
                }
                if ($addMutationLabelToSchemaFieldDescription) {
                    $this->addMutationLabelToSchemaFieldDescription($itemPath);
                }
            }
            foreach (array_keys($typeSchemaDefinition[SchemaDefinition::CONNECTIONS]) as $connectionName) {
                $itemPath = [
                    SchemaDefinition::TYPES,
                    $typeSchemaKey,
                    SchemaDefinition::CONNECTIONS,
                    $connectionName
                ];
                $this->introduceSDLNotationToFieldSchemaDefinition($itemPath);
                if ($addVersionToSchemaFieldDescription) {
                    $this->addVersionToSchemaFieldDescription($itemPath);
                }
                if ($addMutationLabelToSchemaFieldDescription) {
                    $this->addMutationLabelToSchemaFieldDescription($itemPath);
                }
            }
            foreach (array_keys($typeSchemaDefinition[SchemaDefinition::DIRECTIVES]) as $directiveName) {
                $itemPath = [
                    SchemaDefinition::TYPES,
                    $typeSchemaKey,
                    SchemaDefinition::DIRECTIVES,
                    $directiveName
                ];
                $this->introduceSDLNotationToFieldOrDirectiveArgs($itemPath);
                if ($enableComposableDirectives) {
                    $this->addNestedDirectiveDataToSchemaDirectiveArgs($itemPath);
                }
                if ($addVersionToSchemaFieldDescription) {
                    $this->addVersionToSchemaFieldDescription($itemPath);
                }
            }
        }
        // 3. Interfaces
        foreach ($this->fullSchemaDefinition[SchemaDefinition::INTERFACES] as $interfaceName => $interfaceSchemaDefinition) {
            foreach (array_keys($interfaceSchemaDefinition[SchemaDefinition::FIELDS]) as $fieldName) {
                $itemPath = [
                    SchemaDefinition::INTERFACES,
                    $interfaceName,
                    SchemaDefinition::FIELDS,
                    $fieldName
                ];
                $this->introduceSDLNotationToFieldSchemaDefinition($itemPath);
                // if ($addVersionToSchemaFieldDescription) {
                //     $this->addVersionToSchemaFieldDescription($itemPath);
                // }
            }
        }

        // Sort the elements in the schema alphabetically
        if (ComponentConfiguration::sortSchemaAlphabetically()) {
            // Sort types
            ksort($this->fullSchemaDefinition[SchemaDefinition::TYPES]);

            // Sort fields, connections and interfaces for each type
            foreach ($this->fullSchemaDefinition[SchemaDefinition::TYPES] as $typeSchemaKey => $typeSchemaDefinition) {
                if (isset($this->fullSchemaDefinition[SchemaDefinition::TYPES][$typeSchemaKey][SchemaDefinition::FIELDS])) {
                    ksort($this->fullSchemaDefinition[SchemaDefinition::TYPES][$typeSchemaKey][SchemaDefinition::FIELDS]);
                }
                if (isset($this->fullSchemaDefinition[SchemaDefinition::TYPES][$typeSchemaKey][SchemaDefinition::CONNECTIONS])) {
                    ksort($this->fullSchemaDefinition[SchemaDefinition::TYPES][$typeSchemaKey][SchemaDefinition::CONNECTIONS]);
                }
                if (isset($this->fullSchemaDefinition[SchemaDefinition::TYPES][$typeSchemaKey][SchemaDefinition::INTERFACES])) {
                    sort($this->fullSchemaDefinition[SchemaDefinition::TYPES][$typeSchemaKey][SchemaDefinition::INTERFACES]);
                }
            }

            // Sort directives
            if (isset($this->fullSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES])) {
                ksort($this->fullSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES]);
            }
            /**
             * Can NOT sort interfaces yet! Because interfaces may depend on other interfaces,
             * they must follow their current order to be initialized,
             * which happens when creating instances of `InterfaceType` in type `Schema`
             *
             * @todo Find a workaround if interfaces need to be sorted
             */
            // if (isset($this->fullSchemaDefinition[SchemaDefinition::INTERFACES])) {
            //     ksort($this->fullSchemaDefinition[SchemaDefinition::INTERFACES]);
            // }
        }

        // Expand the full schema with more data that is needed for GraphQL
        // Add the scalar types
        $scalarTypeNames = [
            GraphQLServerSchemaDefinitionTypes::TYPE_ID,
            GraphQLServerSchemaDefinitionTypes::TYPE_STRING,
            GraphQLServerSchemaDefinitionTypes::TYPE_INT,
            GraphQLServerSchemaDefinitionTypes::TYPE_FLOAT,
            GraphQLServerSchemaDefinitionTypes::TYPE_BOOL,
            GraphQLServerSchemaDefinitionTypes::TYPE_OBJECT,
            GraphQLServerSchemaDefinitionTypes::TYPE_ANY_SCALAR,
            GraphQLServerSchemaDefinitionTypes::TYPE_MIXED,
            GraphQLServerSchemaDefinitionTypes::TYPE_ARRAY_KEY,
            GraphQLServerSchemaDefinitionTypes::TYPE_DATE,
            GraphQLServerSchemaDefinitionTypes::TYPE_TIME,
            GraphQLServerSchemaDefinitionTypes::TYPE_URL,
            GraphQLServerSchemaDefinitionTypes::TYPE_EMAIL,
            GraphQLServerSchemaDefinitionTypes::TYPE_IP,
        ];
        foreach ($scalarTypeNames as $scalarTypeName) {
            $this->fullSchemaDefinition[SchemaDefinition::TYPES][$scalarTypeName] = [
                SchemaDefinition::NAME => $scalarTypeName,
                SchemaDefinition::NAMESPACED_NAME => $scalarTypeName,
                SchemaDefinition::ELEMENT_NAME => $scalarTypeName,
                SchemaDefinition::DESCRIPTION => null,
                SchemaDefinition::DIRECTIVES => null,
                SchemaDefinition::FIELDS => null,
                SchemaDefinition::CONNECTIONS => null,
                SchemaDefinition::INTERFACES => null,
            ];
        }
    }
    /**
     * Convert the field type from its internal representation (eg: "array:Post") to the GraphQL standard representation (eg: "[Post]")
     */
    protected function introduceSDLNotationToFieldSchemaDefinition(array $fieldSchemaDefinitionPath): void
    {
        $fieldSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $fieldSchemaDefinitionPath);
        $typeName = $fieldSchemaDefinition[SchemaDefinition::TYPE_NAME];
        $fieldSchemaDefinition[SchemaDefinition::TYPE_NAME] = SchemaHelpers::getTypeToOutputInSchema(
            $typeName,
            $fieldSchemaDefinition[SchemaDefinition::NON_NULLABLE] ?? null,
            $fieldSchemaDefinition[SchemaDefinition::IS_ARRAY] ?? false,
            $fieldSchemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false,
            $fieldSchemaDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false,
            $fieldSchemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false,
        );
        $this->introduceSDLNotationToFieldOrDirectiveArgs($fieldSchemaDefinitionPath);
    }
    protected function introduceSDLNotationToFieldOrDirectiveArgs(array $fieldOrDirectiveSchemaDefinitionPath): void
    {
        $fieldOrDirectiveSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $fieldOrDirectiveSchemaDefinitionPath);

        // Also for the fieldOrDirective arguments
        if ($fieldOrDirectiveArgs = $fieldOrDirectiveSchemaDefinition[SchemaDefinition::ARGS] ?? null) {
            foreach ($fieldOrDirectiveArgs as $fieldOrDirectiveArgName => $fieldOrDirectiveArgSchemaDefinition) {
                // The type is set always
                $typeName = $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::TYPE_NAME];
                $fieldOrDirectiveSchemaDefinition[SchemaDefinition::ARGS][$fieldOrDirectiveArgName][SchemaDefinition::TYPE_NAME] = SchemaHelpers::getTypeToOutputInSchema(
                    $typeName,
                    $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::MANDATORY] ?? null,
                    $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::IS_ARRAY] ?? false,
                    $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false,
                    $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false,
                    $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false,
                );
                // If it is an input object, it may have its own args to also convert
                if ($typeName === SchemaDefinitionTypes::TYPE_INPUT_OBJECT) {
                    foreach (($fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGS] ?? []) as $inputFieldArgName => $inputFieldArgDefinition) {
                        $inputFieldTypeName = $inputFieldArgDefinition[SchemaDefinition::TYPE_NAME];
                        $fieldOrDirectiveSchemaDefinition[SchemaDefinition::ARGS][$fieldOrDirectiveArgName][SchemaDefinition::ARGS][$inputFieldArgName][SchemaDefinition::TYPE_NAME] = SchemaHelpers::getTypeToOutputInSchema(
                            $inputFieldTypeName,
                            $inputFieldArgDefinition[SchemaDefinition::MANDATORY] ?? null,
                            $inputFieldArgDefinition[SchemaDefinition::IS_ARRAY] ?? false,
                            $inputFieldArgDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false,
                            $inputFieldArgDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false,
                            $inputFieldArgDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false,
                        );
                    }
                }
            }
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
            $directiveSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $directiveSchemaDefinitionPath);
            if ($directiveSchemaDefinition[SchemaDefinition::DIRECTIVE_TYPE] == DirectiveTypes::SCHEMA) {
                $directiveSchemaDefinition[SchemaDefinition::DESCRIPTION] = sprintf(
                    $this->translationAPI->__('%s %s', 'graphql-server'),
                    sprintf(
                        '_%s_', // Make it italic using markdown
                        $this->translationAPI->__('("Schema" type directive)', 'graphql-server')
                    ),
                    $directiveSchemaDefinition[SchemaDefinition::DESCRIPTION]
                );
            }
        }
    }

    /**
     * Append the field or directive's version to its description
     */
    protected function addVersionToSchemaFieldDescription(array $fieldOrDirectiveSchemaDefinitionPath): void
    {
        $fieldOrDirectiveSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $fieldOrDirectiveSchemaDefinitionPath);
        if ($schemaFieldVersion = $fieldOrDirectiveSchemaDefinition[SchemaDefinition::VERSION] ?? null) {
            $fieldOrDirectiveSchemaDefinition[SchemaDefinition::DESCRIPTION] .= sprintf(
                sprintf(
                    $this->translationAPI->__(' _%s_', 'graphql-server'), // Make it italic using markdown
                    $this->translationAPI->__('(Version: %s)', 'graphql-server')
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
        $directiveSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $directiveSchemaDefinitionPath);
        $directiveSchemaDefinition[SchemaDefinition::ARGS] ??= [];
        $directiveSchemaDefinition[SchemaDefinition::ARGS][] = [
            SchemaDefinition::NAME => SchemaElements::DIRECTIVE_PARAM_NESTED_UNDER,
            SchemaDefinition::TYPE_NAME => GraphQLServerSchemaDefinitionTypes::TYPE_INT,
            SchemaDefinition::DESCRIPTION => $this->translationAPI->__('Nest the directive under another one, indicated as a relative position from this one (a negative int)', 'graphql-server'),
        ];
    }

    /**
     * Append the "Mutation" label to the field's description
     */
    protected function addMutationLabelToSchemaFieldDescription(array $fieldSchemaDefinitionPath): void
    {
        $fieldSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $fieldSchemaDefinitionPath);
        if ($fieldSchemaDefinition[SchemaDefinition::FIELD_IS_MUTATION] ?? null) {
            $fieldSchemaDefinition[SchemaDefinition::DESCRIPTION] = sprintf(
                $this->translationAPI->__('[Mutation] %s', 'graphql-server'),
                $fieldSchemaDefinition[SchemaDefinition::DESCRIPTION]
            );
        }
    }

    public function registerSchemaDefinitionReference(
        AbstractSchemaDefinitionReferenceObject $referenceObject
    ): string {
        $schemaDefinitionPath = $referenceObject->getSchemaDefinitionPath();
        $referenceObjectID = SchemaDefinitionHelpers::getID($schemaDefinitionPath);
        // Calculate and set the ID. If this is a nested type, its wrapping type will already have been registered under this ID
        // Hence, register it under another one
        while (isset($this->fullSchemaDefinitionReferenceDictionary[$referenceObjectID])) {
            // Append the ID with a distinctive token at the end
            $referenceObjectID .= '*';
        }
        $this->fullSchemaDefinitionReferenceDictionary[$referenceObjectID] = $referenceObject;

        // Dynamic types are stored so that the schema can add them to its "types" field
        if ($referenceObject->isDynamicType()) {
            /** @var AbstractDynamicType */
            $referenceObject = $referenceObject;
            $this->dynamicTypes[] = $referenceObject;
        }
        return $referenceObjectID;
    }
    public function getSchemaDefinitionReference(
        string $referenceObjectID
    ): ?AbstractSchemaDefinitionReferenceObject {
        return $this->fullSchemaDefinitionReferenceDictionary[$referenceObjectID];
    }

    public function getDynamicTypes(bool $filterRepeated = true): array
    {
        // Watch out! When an ObjectType or InterfaceType implements an interface,
        // and a field of dynamicType (such as "status", which is an ENUM)
        // is covered by the interface, then the field definition will be
        // that one from the interface's perspective.
        // Hence, this field may be registered several times, as coming
        // from different ObjectTypes implementing the same interface!
        // (Eg: both Post and Page have field "status" from interface CustomPost)
        // If $filterRepeated is true, remove instances with a repeated name
        if ($filterRepeated) {
            $dynamicTypes = $typeNames = [];
            foreach ($this->dynamicTypes as $type) {
                $typeName = $type->getName();
                if (!in_array($typeName, $typeNames)) {
                    $dynamicTypes[] = $type;
                    $typeNames[] = $typeName;
                }
            }
            return $dynamicTypes;
        }
        return $this->dynamicTypes;
    }
}
