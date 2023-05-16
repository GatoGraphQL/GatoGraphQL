<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\ObjectModels\Field;
use GraphQLByPoP\GraphQLServer\ObjectModels\ObjectType;
use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\FieldObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class SchemaObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?FieldObjectTypeResolver $fieldObjectTypeResolver = null;
    private ?GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setFieldObjectTypeResolver(FieldObjectTypeResolver $fieldObjectTypeResolver): void
    {
        $this->fieldObjectTypeResolver = $fieldObjectTypeResolver;
    }
    final protected function getFieldObjectTypeResolver(): FieldObjectTypeResolver
    {
        /** @var FieldObjectTypeResolver */
        return $this->fieldObjectTypeResolver ??= $this->instanceManager->getInstance(FieldObjectTypeResolver::class);
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

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            SchemaObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return array_merge(
            [],
            $moduleConfiguration->exposeGlobalFieldsInGraphQLSchema() ? [
                'globalFields',
            ] : []
        );
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'globalFields' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'globalFields' => $this->__('[Custom introspection field] All global fields (i.e. fields which are added to all types in the schema)', 'graphql-server'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'globalFields' => [
                'includeDeprecated' => $this->getBooleanScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['globalFields' => 'includeDeprecated'] => $this->__('Include deprecated fields?', 'graphql-server'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ($fieldArgName) {
            'includeDeprecated' => false,
            default => parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var Schema */
        $schema = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'globalFields':
                /**
                 * Get the QueryRoot type from the schema,
                 * and obtain the global fields from it.
                 *
                 * Likewise, attach the "global mutations"
                 * from the MutationRoot type.
                 *
                 * Get it from these types, because by default env var
                 * `EXPOSE_GLOBAL_FIELDS_IN_ROOT_TYPE_ONLY_IN_GRAPHQL_SCHEMA`
                 * is enabled.
                 */
                $graphQLSchemaDefinitionService = $this->getGraphQLSchemaDefinitionService();
                $queryRootNamespacedTypeName = $graphQLSchemaDefinitionService->getSchemaQueryRootObjectTypeResolver()->getNamespacedTypeName();

                /** @var ObjectType */
                $queryRootType = $schema->getType($queryRootNamespacedTypeName);
                $queryRootTypeFields = $queryRootType->getFields(
                    $fieldDataAccessor->getValue('includeDeprecated') ?? false,
                    true
                );
                $queryAndMutationRootTypeFields = $queryRootTypeFields;

                $schemaMutationRootObjectTypeResolver = $graphQLSchemaDefinitionService->getSchemaMutationRootObjectTypeResolver();
                if ($schemaMutationRootObjectTypeResolver !== null) {
                    $mutationRootNamespacedTypeName = $schemaMutationRootObjectTypeResolver->getNamespacedTypeName();

                    /** @var ObjectType */
                    $mutationRootType = $schema->getType($mutationRootNamespacedTypeName);
                    $mutationRootTypeFields = $mutationRootType->getFields(
                        $fieldDataAccessor->getValue('includeDeprecated') ?? false,
                        true
                    );

                    $queryAndMutationRootTypeFields = array_merge(
                        $queryAndMutationRootTypeFields,
                        $mutationRootTypeFields
                    );
                }

                $globalFields = array_filter(
                    $queryAndMutationRootTypeFields,
                    fn (Field $field) => $field->getExtensions()->isGlobal(),
                );
                return array_map(
                    fn (Field $field) => $field->getID(),
                    $globalFields,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'globalFields' => $this->getFieldObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
