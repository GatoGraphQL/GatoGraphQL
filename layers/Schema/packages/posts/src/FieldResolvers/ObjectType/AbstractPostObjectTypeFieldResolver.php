<?php

declare(strict_types=1);

namespace PoPSchema\Posts\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\Posts\TypeResolvers\InputObjectType\PostPaginationInputObjectTypeResolver;
use PoPSchema\Posts\TypeResolvers\InputObjectType\RootPostsFilterInputObjectTypeResolver;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

abstract class AbstractPostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?RootPostsFilterInputObjectTypeResolver $rootPostsFilterInputObjectTypeResolver = null;
    private ?PostPaginationInputObjectTypeResolver $postPaginationInputObjectTypeResolver = null;
    private ?CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?PostTypeAPIInterface $postTypeAPI = null;

    final public function setRootPostsFilterInputObjectTypeResolver(RootPostsFilterInputObjectTypeResolver $rootPostsFilterInputObjectTypeResolver): void
    {
        $this->rootPostsFilterInputObjectTypeResolver = $rootPostsFilterInputObjectTypeResolver;
    }
    final protected function getRootPostsFilterInputObjectTypeResolver(): RootPostsFilterInputObjectTypeResolver
    {
        return $this->rootPostsFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootPostsFilterInputObjectTypeResolver::class);
    }
    final public function setPostPaginationInputObjectTypeResolver(PostPaginationInputObjectTypeResolver $postPaginationInputObjectTypeResolver): void
    {
        $this->postPaginationInputObjectTypeResolver = $postPaginationInputObjectTypeResolver;
    }
    final protected function getPostPaginationInputObjectTypeResolver(): PostPaginationInputObjectTypeResolver
    {
        return $this->postPaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(PostPaginationInputObjectTypeResolver::class);
    }
    final public function setCustomPostSortInputObjectTypeResolver(CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver): void
    {
        $this->customPostSortInputObjectTypeResolver = $customPostSortInputObjectTypeResolver;
    }
    final protected function getCustomPostSortInputObjectTypeResolver(): CustomPostSortInputObjectTypeResolver
    {
        return $this->customPostSortInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostSortInputObjectTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setPostTypeAPI(PostTypeAPIInterface $postTypeAPI): void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        return $this->postTypeAPI ??= $this->instanceManager->getInstance(PostTypeAPIInterface::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'posts',
            'postCount',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'posts' => $this->getPostObjectTypeResolver(),
            'postCount' => $this->getIntScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'postCount' => SchemaTypeModifiers::NON_NULLABLE,
            'posts' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'posts' => $this->__('Posts', 'pop-posts'),
            'postCount' => $this->__('Number of posts', 'pop-posts'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'posts' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootPostsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getPostPaginationInputObjectTypeResolver(),
                    'sort' => $this->getCustomPostSortInputObjectTypeResolver(),
                ]
            ),
            'postCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootPostsFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs
    ): array {
        return [];
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs),
            $this->getQuery($objectTypeResolver, $object, $fieldName, $fieldArgs)
        );
        switch ($fieldName) {
            case 'posts':
                return $this->getPostTypeAPI()->getPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'postCount':
                return $this->getPostTypeAPI()->getPostCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
