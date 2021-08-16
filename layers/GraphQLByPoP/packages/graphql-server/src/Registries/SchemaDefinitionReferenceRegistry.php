<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Registries;

use GraphQLByPoP\GraphQLQuery\ComponentConfiguration as GraphQLQueryComponentConfiguration;
use GraphQLByPoP\GraphQLQuery\Schema\SchemaElements;
use GraphQLByPoP\GraphQLServer\Cache\CacheTypes;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractDynamicType;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition as GraphQLServerSchemaDefinition;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use GraphQLByPoP\GraphQLServer\Schema\SchemaHelpers;
use PoP\API\Cache\CacheUtils;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use PoP\API\Facades\SchemaDefinitionRegistryFacade;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Translation\TranslationAPIInterface;

class SchemaDefinitionReferenceRegistry implements SchemaDefinitionReferenceRegistryInterface
{
    /**
     * @var array<string, mixed>
     */
    protected ?array $fullSchemaDefinition = null;
    /**
     * @var array<string, AbstractSchemaDefinitionReferenceObject>
     */
    protected array $fullSchemaDefinitionReferenceDictionary = [];
    /**
     * @var AbstractDynamicType[]
     */
    protected array $dynamicTypes = [];

    public function __construct(
        protected TranslationAPIInterface $translationAPI,
        protected SchemaDefinitionServiceInterface $schemaDefinitionService,
    ) {
    }

    /**
     * It returns the full schema, expanded with all data required to satisfy
     * GraphQL's introspection fields (starting from "__schema")
     *
     * It can store the value in the cache.
     * Use cache with care: if the schema is dynamic, it should not be cached.
     * Public schema: can cache, Private schema: cannot cache.
     */
    public function &getFullSchemaDefinition(): array
    {
        if (is_null($this->fullSchemaDefinition)) {
            // These are the configuration options to work with the "full schema"
            $fieldArgs = [
                'deep' => true,
                'shape' => SchemaDefinition::ARGVALUE_SCHEMA_SHAPE_FLAT,
                'compressed' => true,
                'useTypeName' => true,
            ];

            // Attempt to retrieve from the cache, if enabled
            if ($useCache = APIComponentConfiguration::useSchemaDefinitionCache()) {
                $persistentCache = PersistentCacheFacade::getInstance();
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
                if ($persistentCache->hasCache($cacheKey, $cacheType)) {
                    $this->fullSchemaDefinition = $persistentCache->getCache($cacheKey, $cacheType);
                }
            }

            // If either not using cache, or using but the value had not been cached, then calculate the value
            if (!$this->fullSchemaDefinition) {
                // Get the schema definitions
                $schemaDefinitionRegistry = SchemaDefinitionRegistryFacade::getInstance();
                $this->fullSchemaDefinition = $schemaDefinitionRegistry->getSchemaDefinition($fieldArgs);

                // Convert the schema from PoP's format to what GraphQL needs to work with
                $this->prepareSchemaDefinitionForGraphQL();

                // Store in the cache
                if ($useCache) {
                    $persistentCache->storeCache($cacheKey, $cacheType, $this->fullSchemaDefinition);
                }
            }
        }

        return $this->fullSchemaDefinition;
    }
    protected function prepareSchemaDefinitionForGraphQL(): void
    {
        $vars = ApplicationState::getVars();
        $enableNestedMutations = $vars['nested-mutations-enabled'];

        $graphQLSchemaDefinitionService = GraphQLSchemaDefinitionServiceFacade::getInstance();
        $rootTypeSchemaKey = $graphQLSchemaDefinitionService->getRootTypeSchemaKey();
        $queryRootTypeSchemaKey = null;
        if (!$enableNestedMutations) {
            $queryRootTypeSchemaKey = $graphQLSchemaDefinitionService->getQueryRootTypeSchemaKey();
        }

        // Remove the introspection fields that must not be added to the schema
        // Field "__typename" from all types (GraphQL spec @ https://graphql.github.io/graphql-spec/draft/#sel-FAJZHABFBKjrL):
        // "This field is implicit and does not appear in the fields list in any defined type."
        unset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_GLOBAL_FIELDS]['__typename']);

        // Fields "__schema" and "__type" from the query type (GraphQL spec @ https://graphql.github.io/graphql-spec/draft/#sel-FAJbHABABnD9ub):
        // "These fields are implicit and do not appear in the fields list in the root type of the query operation."
        unset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$rootTypeSchemaKey][SchemaDefinition::ARGNAME_CONNECTIONS]['__type']);
        unset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$rootTypeSchemaKey][SchemaDefinition::ARGNAME_CONNECTIONS]['__schema']);
        if (!$enableNestedMutations) {
            unset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$queryRootTypeSchemaKey][SchemaDefinition::ARGNAME_CONNECTIONS]['__type']);
            unset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$queryRootTypeSchemaKey][SchemaDefinition::ARGNAME_CONNECTIONS]['__schema']);
        }

        // Remove unneeded data
        if (!ComponentConfiguration::addGlobalFieldsToSchema()) {
            unset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_GLOBAL_FIELDS]);
            unset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS]);
        }
        if (!ComponentConfiguration::addSelfFieldToSchema()) {
            /**
             * Check if to remove the "self" field everywhere, or if to keep it just for the Root type
             */
            $keepSelfFieldForRootType = ComponentConfiguration::addSelfFieldForRootTypeToSchema();
            foreach (array_keys($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES]) as $typeSchemaKey) {
                if (!$keepSelfFieldForRootType || ($typeSchemaKey != $rootTypeSchemaKey && ($enableNestedMutations || $typeSchemaKey != $queryRootTypeSchemaKey))) {
                    unset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][SchemaDefinition::ARGNAME_CONNECTIONS]['self']);
                }
            }
        }
        if (!ComponentConfiguration::addFullSchemaFieldToSchema()) {
            unset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$rootTypeSchemaKey][SchemaDefinition::ARGNAME_FIELDS]['fullSchema']);
            if (!$enableNestedMutations) {
                unset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$queryRootTypeSchemaKey][SchemaDefinition::ARGNAME_FIELDS]['fullSchema']);
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
            foreach (array_keys($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_GLOBAL_FIELDS]) as $fieldName) {
                $itemPath = [
                    SchemaDefinition::ARGNAME_GLOBAL_FIELDS,
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
            foreach (array_keys($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS]) as $connectionName) {
                $itemPath = [
                    SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS,
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
        foreach (array_keys($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES]) as $directiveName) {
            if (!in_array($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES][$directiveName][SchemaDefinition::ARGNAME_DIRECTIVE_TYPE], $supportedDirectiveTypes)) {
                $directivesNamesToRemove[] = $directiveName;
            }
        }
        foreach ($directivesNamesToRemove as $directiveName) {
            unset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES][$directiveName]);
        }
        // Add the directives
        foreach (array_keys($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES]) as $directiveName) {
            $itemPath = [
                SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES,
                $directiveName
            ];
            $fieldOrDirectiveSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $itemPath);

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
        foreach ($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES] as $typeSchemaKey => $typeSchemaDefinition) {
            // No need for Union types
            if ($typeSchemaDefinition[SchemaDefinition::ARGNAME_IS_UNION] ?? null) {
                continue;
            }
            foreach (array_keys($typeSchemaDefinition[SchemaDefinition::ARGNAME_FIELDS]) as $fieldName) {
                $itemPath = [
                    SchemaDefinition::ARGNAME_TYPES,
                    $typeSchemaKey,
                    SchemaDefinition::ARGNAME_FIELDS,
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
            foreach (array_keys($typeSchemaDefinition[SchemaDefinition::ARGNAME_CONNECTIONS]) as $connectionName) {
                $itemPath = [
                    SchemaDefinition::ARGNAME_TYPES,
                    $typeSchemaKey,
                    SchemaDefinition::ARGNAME_CONNECTIONS,
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
            foreach (array_keys($typeSchemaDefinition[SchemaDefinition::ARGNAME_DIRECTIVES]) as $directiveName) {
                $itemPath = [
                    SchemaDefinition::ARGNAME_TYPES,
                    $typeSchemaKey,
                    SchemaDefinition::ARGNAME_DIRECTIVES,
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
        foreach ($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_INTERFACES] as $interfaceName => $interfaceSchemaDefinition) {
            foreach (array_keys($interfaceSchemaDefinition[SchemaDefinition::ARGNAME_FIELDS]) as $fieldName) {
                $itemPath = [
                    SchemaDefinition::ARGNAME_INTERFACES,
                    $interfaceName,
                    SchemaDefinition::ARGNAME_FIELDS,
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
            ksort($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES]);

            // Sort fields, connections and interfaces for each type
            foreach ($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES] as $typeSchemaKey => $typeSchemaDefinition) {
                if (isset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][SchemaDefinition::ARGNAME_FIELDS])) {
                    ksort($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][SchemaDefinition::ARGNAME_FIELDS]);
                }
                if (isset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][SchemaDefinition::ARGNAME_CONNECTIONS])) {
                    ksort($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][SchemaDefinition::ARGNAME_CONNECTIONS]);
                }
                if (isset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][SchemaDefinition::ARGNAME_INTERFACES])) {
                    sort($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][SchemaDefinition::ARGNAME_INTERFACES]);
                }
            }

            // Sort directives
            if (isset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES])) {
                ksort($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES]);
            }
            /**
             * Can NOT sort interfaces yet! Because interfaces may depend on other interfaces,
             * they must follow their current order to be initialized,
             * which happens when creating instances of `InterfaceType` in type `Schema`
             *
             * @todo Find a workaround if interfaces need to be sorted
             */
            // if (isset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_INTERFACES])) {
            //     ksort($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_INTERFACES]);
            // }
        }

        // Expand the full schema with more data that is needed for GraphQL
        // Add the scalar types
        $scalarTypeNames = [
            GraphQLServerSchemaDefinition::TYPE_ID,
            GraphQLServerSchemaDefinition::TYPE_STRING,
            GraphQLServerSchemaDefinition::TYPE_INT,
            GraphQLServerSchemaDefinition::TYPE_FLOAT,
            GraphQLServerSchemaDefinition::TYPE_BOOL,
            GraphQLServerSchemaDefinition::TYPE_OBJECT,
            GraphQLServerSchemaDefinition::TYPE_ANY_SCALAR,
            GraphQLServerSchemaDefinition::TYPE_MIXED,
            GraphQLServerSchemaDefinition::TYPE_ARRAY_KEY,
            GraphQLServerSchemaDefinition::TYPE_DATE,
            GraphQLServerSchemaDefinition::TYPE_TIME,
            GraphQLServerSchemaDefinition::TYPE_URL,
            GraphQLServerSchemaDefinition::TYPE_EMAIL,
            GraphQLServerSchemaDefinition::TYPE_IP,
        ];
        foreach ($scalarTypeNames as $scalarTypeName) {
            $this->fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES][$scalarTypeName] = [
                SchemaDefinition::ARGNAME_NAME => $scalarTypeName,
                SchemaDefinition::ARGNAME_NAMESPACED_NAME => $scalarTypeName,
                SchemaDefinition::ARGNAME_ELEMENT_NAME => $scalarTypeName,
                SchemaDefinition::ARGNAME_DESCRIPTION => null,
                SchemaDefinition::ARGNAME_DIRECTIVES => null,
                SchemaDefinition::ARGNAME_FIELDS => null,
                SchemaDefinition::ARGNAME_CONNECTIONS => null,
                SchemaDefinition::ARGNAME_INTERFACES => null,
            ];
        }
    }
    /**
     * Convert the field type from its internal representation (eg: "array:Post") to the GraphQL standard representation (eg: "[Post]")
     */
    protected function introduceSDLNotationToFieldSchemaDefinition(array $fieldSchemaDefinitionPath): void
    {
        $fieldSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $fieldSchemaDefinitionPath);
        $type = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE];
        $fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE] = SchemaHelpers::getTypeToOutputInSchema(
            $type,
            $fieldSchemaDefinition[SchemaDefinition::ARGNAME_NON_NULLABLE] ?? null,
            $fieldSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] ?? false,
            $fieldSchemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false,
            $fieldSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] ?? false,
            $fieldSchemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false,
        );
        $this->introduceSDLNotationToFieldOrDirectiveArgs($fieldSchemaDefinitionPath);
    }
    protected function introduceSDLNotationToFieldOrDirectiveArgs(array $fieldOrDirectiveSchemaDefinitionPath): void
    {
        $fieldOrDirectiveSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $fieldOrDirectiveSchemaDefinitionPath);

        // Also for the fieldOrDirective arguments
        if ($fieldOrDirectiveArgs = $fieldOrDirectiveSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? null) {
            foreach ($fieldOrDirectiveArgs as $fieldOrDirectiveArgName => $fieldOrDirectiveArgSchemaDefinition) {
                // The type is mandatory. If not provided, use the default one
                $type = $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_TYPE] ?? $this->schemaDefinitionService->getDefaultType();
                $fieldOrDirectiveSchemaDefinition[SchemaDefinition::ARGNAME_ARGS][$fieldOrDirectiveArgName][SchemaDefinition::ARGNAME_TYPE] = SchemaHelpers::getTypeToOutputInSchema(
                    $type,
                    $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_MANDATORY] ?? null,
                    $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] ?? false,
                    $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false,
                    $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] ?? false,
                    $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false,
                );
                // If it is an input object, it may have its own args to also convert
                if ($type == SchemaDefinition::TYPE_INPUT_OBJECT) {
                    foreach (($fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? []) as $inputFieldArgName => $inputFieldArgDefinition) {
                        $inputFieldType = $inputFieldArgDefinition[SchemaDefinition::ARGNAME_TYPE];
                        $fieldOrDirectiveSchemaDefinition[SchemaDefinition::ARGNAME_ARGS][$fieldOrDirectiveArgName][SchemaDefinition::ARGNAME_ARGS][$inputFieldArgName][SchemaDefinition::ARGNAME_TYPE] = SchemaHelpers::getTypeToOutputInSchema(
                            $inputFieldType,
                            $inputFieldArgDefinition[SchemaDefinition::ARGNAME_MANDATORY] ?? null,
                            $inputFieldArgDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] ?? false,
                            $inputFieldArgDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false,
                            $inputFieldArgDefinition[SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] ?? false,
                            $inputFieldArgDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false,
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
            if ($directiveSchemaDefinition[SchemaDefinition::ARGNAME_DIRECTIVE_TYPE] == DirectiveTypes::SCHEMA) {
                $directiveSchemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION] = sprintf(
                    $this->translationAPI->__('%s %s', 'graphql-server'),
                    sprintf(
                        '_%s_', // Make it italic using markdown
                        $this->translationAPI->__('("Schema" type directive)', 'graphql-server')
                    ),
                    $directiveSchemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION]
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
        if ($schemaFieldVersion = $fieldOrDirectiveSchemaDefinition[SchemaDefinition::ARGNAME_VERSION] ?? null) {
            $fieldOrDirectiveSchemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION] .= sprintf(
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
        $directiveSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ??= [];
        $directiveSchemaDefinition[SchemaDefinition::ARGNAME_ARGS][] = [
            SchemaDefinition::ARGNAME_NAME => SchemaElements::DIRECTIVE_PARAM_NESTED_UNDER,
            SchemaDefinition::ARGNAME_TYPE => GraphQLServerSchemaDefinition::TYPE_INT,
            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Nest the directive under another one, indicated as a relative position from this one (a negative int)', 'graphql-server'),
        ];
    }

    /**
     * Append the "Mutation" label to the field's description
     */
    protected function addMutationLabelToSchemaFieldDescription(array $fieldSchemaDefinitionPath): void
    {
        $fieldSchemaDefinition = &SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $fieldSchemaDefinitionPath);
        if ($fieldSchemaDefinition[SchemaDefinition::ARGNAME_FIELD_IS_MUTATION] ?? null) {
            $fieldSchemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION] = sprintf(
                $this->translationAPI->__('[Mutation] %s', 'graphql-server'),
                $fieldSchemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION]
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
