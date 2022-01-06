<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostPaginationInputObjectTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

abstract class AbstractCustomPostListObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostPaginationInputObjectTypeResolver $customPostPaginationInputObjectTypeResolver = null;
    private ?CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    final public function setCustomPostPaginationInputObjectTypeResolver(CustomPostPaginationInputObjectTypeResolver $customPostPaginationInputObjectTypeResolver): void
    {
        $this->customPostPaginationInputObjectTypeResolver = $customPostPaginationInputObjectTypeResolver;
    }
    final protected function getCustomPostPaginationInputObjectTypeResolver(): CustomPostPaginationInputObjectTypeResolver
    {
        return $this->customPostPaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostPaginationInputObjectTypeResolver::class);
    }
    final public function setCustomPostSortInputObjectTypeResolver(CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver): void
    {
        $this->customPostSortInputObjectTypeResolver = $customPostSortInputObjectTypeResolver;
    }
    final protected function getCustomPostSortInputObjectTypeResolver(): CustomPostSortInputObjectTypeResolver
    {
        return $this->customPostSortInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostSortInputObjectTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'customPosts',
            'customPostCount',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'customPosts' => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            'customPostCount' => $this->getIntScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'customPostCount' => SchemaTypeModifiers::NON_NULLABLE,
            'customPosts' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'customPosts' => $this->__('Custom posts', 'pop-posts'),
            'customPostCount' => $this->__('Number of custom posts', 'pop-posts'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    abstract protected function getCustomPostsFilterInputObjectTypeResolver(): AbstractCustomPostsFilterInputObjectTypeResolver;

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'customPosts' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getCustomPostsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getCustomPostPaginationInputObjectTypeResolver(),
                    'sort' => $this->getCustomPostSortInputObjectTypeResolver(),
                ]
            ),
            'customPostCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getCustomPostsFilterInputObjectTypeResolver(),
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
            case 'customPosts':
                return $this->getCustomPostTypeAPI()->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'customPostCount':
                return $this->getCustomPostTypeAPI()->getCustomPostCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
