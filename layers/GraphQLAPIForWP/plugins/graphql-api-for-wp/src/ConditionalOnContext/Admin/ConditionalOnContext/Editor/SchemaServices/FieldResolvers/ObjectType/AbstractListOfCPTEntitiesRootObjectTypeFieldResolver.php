<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostPaginationInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\CustomPostsWP\Constants\QueryOptions;
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

/**
 * ObjectTypeFieldResolver for the Custom Post Types from this plugin
 */
abstract class AbstractListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostPaginationInputObjectTypeResolver $customPostPaginationInputObjectTypeResolver = null;
    private ?CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver = null;

    final public function setGenericCustomPostObjectTypeResolver(GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver): void
    {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        /** @var GenericCustomPostObjectTypeResolver */
        return $this->genericCustomPostObjectTypeResolver ??= $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
    }
    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    final public function setCustomPostPaginationInputObjectTypeResolver(CustomPostPaginationInputObjectTypeResolver $customPostPaginationInputObjectTypeResolver): void
    {
        $this->customPostPaginationInputObjectTypeResolver = $customPostPaginationInputObjectTypeResolver;
    }
    final protected function getCustomPostPaginationInputObjectTypeResolver(): CustomPostPaginationInputObjectTypeResolver
    {
        /** @var CustomPostPaginationInputObjectTypeResolver */
        return $this->customPostPaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostPaginationInputObjectTypeResolver::class);
    }
    final public function setCustomPostSortInputObjectTypeResolver(CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver): void
    {
        $this->customPostSortInputObjectTypeResolver = $customPostSortInputObjectTypeResolver;
    }
    final protected function getCustomPostSortInputObjectTypeResolver(): CustomPostSortInputObjectTypeResolver
    {
        /** @var CustomPostSortInputObjectTypeResolver */
        return $this->customPostSortInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostSortInputObjectTypeResolver::class);
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

    abstract protected function getFieldCustomPostType(FieldDataAccessorInterface $fieldDataAccessor): string;

    public function getFieldTypeResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ConcreteTypeResolverInterface {
        return $this->getGenericCustomPostObjectTypeResolver();
    }
}
