<?php

declare(strict_types=1);

namespace PoPSchema\Categories\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractObjectTypeFieldResolverInterface;
use PoPSchema\Categories\TypeResolvers\InputObjectType\CategoryPaginationInputObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\Categories\TypeResolvers\InputObjectType\CustomPostCategoriesFilterInputObjectTypeResolver;
use PoPSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver;

abstract class AbstractCustomPostQueryableObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements CategoryAPIRequestedContractObjectTypeFieldResolverInterface
{
    use WithLimitFieldArgResolverTrait;

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?CustomPostCategoriesFilterInputObjectTypeResolver $customPostCategoriesFilterInputObjectTypeResolver = null;
    private ?CategoryPaginationInputObjectTypeResolver $categoryPaginationInputObjectTypeResolver = null;
    private ?TaxonomySortInputObjectTypeResolver $taxonomySortInputObjectTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setCustomPostCategoriesFilterInputObjectTypeResolver(CustomPostCategoriesFilterInputObjectTypeResolver $customPostCategoriesFilterInputObjectTypeResolver): void
    {
        $this->customPostCategoriesFilterInputObjectTypeResolver = $customPostCategoriesFilterInputObjectTypeResolver;
    }
    final protected function getCustomPostCategoriesFilterInputObjectTypeResolver(): CustomPostCategoriesFilterInputObjectTypeResolver
    {
        return $this->customPostCategoriesFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostCategoriesFilterInputObjectTypeResolver::class);
    }
    final public function setCategoryPaginationInputObjectTypeResolver(CategoryPaginationInputObjectTypeResolver $categoryPaginationInputObjectTypeResolver): void
    {
        $this->categoryPaginationInputObjectTypeResolver = $categoryPaginationInputObjectTypeResolver;
    }
    final protected function getCategoryPaginationInputObjectTypeResolver(): CategoryPaginationInputObjectTypeResolver
    {
        return $this->categoryPaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(CategoryPaginationInputObjectTypeResolver::class);
    }
    final public function setTaxonomySortInputObjectTypeResolver(TaxonomySortInputObjectTypeResolver $taxonomySortInputObjectTypeResolver): void
    {
        $this->taxonomySortInputObjectTypeResolver = $taxonomySortInputObjectTypeResolver;
    }
    final protected function getTaxonomySortInputObjectTypeResolver(): TaxonomySortInputObjectTypeResolver
    {
        return $this->taxonomySortInputObjectTypeResolver ??= $this->instanceManager->getInstance(TaxonomySortInputObjectTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'categories',
            'categoryCount',
            'categoryNames',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'categories' => $this->getCategoryTypeResolver(),
            'categoryCount' => $this->getIntScalarTypeResolver(),
            'categoryNames' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'categoryCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'categories',
            'categoryNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'categories' => $this->__('Categories added to this custom post', 'pop-categories'),
            'categoryCount' => $this->__('Number of categories added to this custom post', 'pop-categories'),
            'categoryNames' => $this->__('Names of the categories added to this custom post', 'pop-categories'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'categories',
            'categoryNames' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getCustomPostCategoriesFilterInputObjectTypeResolver(),
                    'pagination' => $this->getCategoryPaginationInputObjectTypeResolver(),
                    'sort' => $this->getTaxonomySortInputObjectTypeResolver(),
                ]
            ),
            'categoryCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getCustomPostCategoriesFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
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
        $categoryTypeAPI = $this->getCategoryTypeAPI();
        $post = $object;
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'categories':
                return $categoryTypeAPI->getCustomPostCategories($objectTypeResolver->getID($post), $query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'categoryNames':
                return $categoryTypeAPI->getCustomPostCategories($objectTypeResolver->getID($post), $query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'categoryCount':
                return $categoryTypeAPI->getCustomPostCategoryCount($objectTypeResolver->getID($post), $query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
