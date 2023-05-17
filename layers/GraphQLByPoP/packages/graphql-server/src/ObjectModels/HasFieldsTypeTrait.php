<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoPAPI\API\Schema\SchemaDefinition;
use PoP\Root\App;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

trait HasFieldsTypeTrait
{
    /**
     * @var Field[]
     */
    protected array $fields;

    protected static function getGraphQLSchemaDefinitionService(): GraphQLSchemaDefinitionServiceInterface
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLSchemaDefinitionServiceInterface */
        return $instanceManager->getInstance(GraphQLSchemaDefinitionServiceInterface::class);
    }

    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    protected function initFields(array &$fullSchemaDefinition, array $schemaDefinitionPath): void
    {
        /**
         * Iterate to the definition of the fields in the schema,
         * and create an object for each of them
         */
        $this->fields = SchemaDefinitionHelpers::createFieldsFromPath(
            $fullSchemaDefinition,
            array_merge(
                $schemaDefinitionPath,
                [
                    SchemaDefinition::FIELDS,
                ]
            )
        );

        $globalFields = [];

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->exposeGlobalFieldsInGraphQLSchema()) {
            /**
             * Display the Global Fields either under all types,
             * or only under the Root type ("global fields" can mean
             * "fields" under Query Root, or "mutations" under Mutation Root)
             */
            $exposeGlobalFieldsInRootTypeOnlyInGraphQLSchema = $moduleConfiguration->exposeGlobalFieldsInRootTypeOnlyInGraphQLSchema();
            $graphQLSchemaDefinitionService = $this->getGraphQLSchemaDefinitionService();
            $namespacedName = $this->getNamespacedName();
            $queryRootNamespacedTypeName = $graphQLSchemaDefinitionService->getSchemaQueryRootObjectTypeResolver()->getNamespacedTypeName();
            $mutationRootNamespacedTypeName = $graphQLSchemaDefinitionService->getSchemaMutationRootObjectTypeResolver()?->getNamespacedTypeName();
            if (
                !$exposeGlobalFieldsInRootTypeOnlyInGraphQLSchema
                || $namespacedName === $queryRootNamespacedTypeName
                || $namespacedName === $mutationRootNamespacedTypeName
            ) {
                /**
                 * Global fields have already been initialized,
                 * simply get the reference to the existing objects
                 * from the registryMap
                 */
                $fieldAndMutationGlobalFields = SchemaDefinitionHelpers::getFieldsFromPath(
                    $fullSchemaDefinition,
                    [
                        SchemaDefinition::GLOBAL_FIELDS,
                    ]
                );
                /**
                 * Filter fields/mutations for either QueryRoot or MutationRoot.
                 * Check for the condition MutationRoot first, as its fields
                 * need to be filtered. If it's not any of these, only then
                 * add all globalFields to all other fields.
                 */
                if ($namespacedName === $mutationRootNamespacedTypeName) {
                    $globalFields = array_values(array_filter(
                        $fieldAndMutationGlobalFields,
                        fn (Field $field) => $field->getExtensions()->isMutation()
                    ));
                } else {
                    // Condition satisfied here:
                    //   $namespacedName === $queryRootNamespacedTypeName
                    // or
                    //   !$exposeGlobalFieldsInRootTypeOnlyInGraphQLSchema
                    /**
                     * Field other than MutationRoot (i.e. QueryRoot and all others):
                     * 
                     * - Nested mutations is enabled => also add mutations
                     * - Otherwise, only add fields
                     */
                    $globalFields = $moduleConfiguration->enableNestedMutations()
                        ? $fieldAndMutationGlobalFields
                        : array_values(array_filter(
                            $fieldAndMutationGlobalFields,
                            fn (Field $field) => !$field->getExtensions()->isMutation()
                        ));
                }
            }
        }

        // Maybe sort fields and connections all together
        if ($moduleConfiguration->sortGraphQLSchemaAlphabetically()) {
            if ($moduleConfiguration->sortGlobalFieldsAfterNormalFieldsInGraphQLSchema()) {
                /**
                 * Sort them separately, then merge them
                 */
                uasort($this->fields, fn (Field $a, Field $b) => $a->getName() <=> $b->getName());
                uasort($globalFields, fn (Field $a, Field $b) => $a->getName() <=> $b->getName());
                $this->fields = array_merge(
                    $this->fields,
                    $globalFields
                );
            } else {
                /**
                 * Merge them, then sort them together
                 */
                $this->fields = array_merge(
                    $this->fields,
                    $globalFields
                );
                uasort($this->fields, fn (Field $a, Field $b) => $a->getName() <=> $b->getName());
            }
        } else {
            $this->fields = array_merge(
                $this->fields,
                $globalFields
            );
        }
    }

    abstract public function getNamespacedName(): string;

    /**
     * @param bool $includeGlobal Custom parameter by this GraphQL Server (i.e. it is not present in the GraphQL spec)
     * @return Field[]
     */
    public function getFields(
        bool $includeDeprecated = false,
        bool $includeGlobal = true,
    ): array {
        $fields = $this->fields;

        if (!$includeDeprecated) {
            $fields = array_filter(
                $fields,
                fn (Field $field) => !$field->isDeprecated(),
            );
        }

        if (!$includeGlobal) {
            $fields = array_filter(
                $fields,
                fn (Field $field) => !$field->getExtensions()->isGlobal(),
            );
        }

        return $fields;
    }

    /**
     * @param bool $includeGlobal Custom parameter by this GraphQL Server (i.e. it is not present in the GraphQL spec)
     * @return string[]
     */
    public function getFieldIDs(
        bool $includeDeprecated = false,
        bool $includeGlobal = true,
    ): array {
        return array_map(
            fn (Field $field) => $field->getID(),
            $this->getFields($includeDeprecated, $includeGlobal)
        );
    }
}
