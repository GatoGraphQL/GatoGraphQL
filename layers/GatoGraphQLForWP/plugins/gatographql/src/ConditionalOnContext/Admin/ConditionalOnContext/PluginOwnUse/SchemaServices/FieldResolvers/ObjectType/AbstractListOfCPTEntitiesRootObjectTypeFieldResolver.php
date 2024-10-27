<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\ConditionalOnContext\PluginOwnUse\SchemaServices\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostsWP\Constants\QueryOptions;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostPaginationInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\RootPredefinedCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions as SchemaCommonsQueryOptions;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

/**
 * ObjectTypeFieldResolver for the Custom Post Types from this plugin
 */
abstract class AbstractListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostPaginationInputObjectTypeResolver $customPostPaginationInputObjectTypeResolver = null;
    private ?CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver = null;
    private ?RootPredefinedCustomPostsFilterInputObjectTypeResolver $rootPredefinedCustomPostsFilterInputObjectTypeResolver = null;

    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        if ($this->genericCustomPostObjectTypeResolver === null) {
            /** @var GenericCustomPostObjectTypeResolver */
            $genericCustomPostObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
            $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        }
        return $this->genericCustomPostObjectTypeResolver;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }
    final protected function getCustomPostPaginationInputObjectTypeResolver(): CustomPostPaginationInputObjectTypeResolver
    {
        if ($this->customPostPaginationInputObjectTypeResolver === null) {
            /** @var CustomPostPaginationInputObjectTypeResolver */
            $customPostPaginationInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostPaginationInputObjectTypeResolver::class);
            $this->customPostPaginationInputObjectTypeResolver = $customPostPaginationInputObjectTypeResolver;
        }
        return $this->customPostPaginationInputObjectTypeResolver;
    }
    final protected function getCustomPostSortInputObjectTypeResolver(): CustomPostSortInputObjectTypeResolver
    {
        if ($this->customPostSortInputObjectTypeResolver === null) {
            /** @var CustomPostSortInputObjectTypeResolver */
            $customPostSortInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostSortInputObjectTypeResolver::class);
            $this->customPostSortInputObjectTypeResolver = $customPostSortInputObjectTypeResolver;
        }
        return $this->customPostSortInputObjectTypeResolver;
    }
    final protected function getRootPredefinedCustomPostsFilterInputObjectTypeResolver(): RootPredefinedCustomPostsFilterInputObjectTypeResolver
    {
        if ($this->rootPredefinedCustomPostsFilterInputObjectTypeResolver === null) {
            /** @var RootPredefinedCustomPostsFilterInputObjectTypeResolver */
            $rootPredefinedCustomPostsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootPredefinedCustomPostsFilterInputObjectTypeResolver::class);
            $this->rootPredefinedCustomPostsFilterInputObjectTypeResolver = $rootPredefinedCustomPostsFilterInputObjectTypeResolver;
        }
        return $this->rootPredefinedCustomPostsFilterInputObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * Do not show in the schema
     */
    public function skipExposingFieldInSchema(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return true;
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return array_merge(
            $fieldArgNameTypeResolvers,
            [
                'pagination' => $this->getCustomPostPaginationInputObjectTypeResolver(),
                'sort' => $this->getCustomPostSortInputObjectTypeResolver(),
                'filter' => $this->getRootPredefinedCustomPostsFilterInputObjectTypeResolver(),
            ]
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        return [
            'limit' => -1,
            // Execute for the corresponding field name
            'custompost-types' => [
                $this->getFieldCustomPostType($fieldDataAccessor),
            ],
        ];
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $query = array_merge(
            $this->getQuery($objectTypeResolver, $object, $fieldDataAccessor),
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor),
        );
        $options = [
            SchemaCommonsQueryOptions::RETURN_TYPE => ReturnTypes::IDS,
            // With this flag, the hook will not remove the private CPTs
            QueryOptions::ALLOW_QUERYING_PRIVATE_CPTS => true,
        ];
        return $this->getCustomPostTypeAPI()->getCustomPosts($query, $options);
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

    abstract protected function getFieldCustomPostType(FieldDataAccessorInterface $fieldDataAccessor): string;

    public function getFieldTypeResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ConcreteTypeResolverInterface {
        return $this->getGenericCustomPostObjectTypeResolver();
    }
}
