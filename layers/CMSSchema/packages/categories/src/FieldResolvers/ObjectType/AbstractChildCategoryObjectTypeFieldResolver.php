<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Categories\ModuleContracts\CategoryAPIRequestedContractObjectTypeFieldResolverInterface;
use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CategoryPaginationInputObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomyTaxonomiesFilterInputObjectTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver;

abstract class AbstractChildCategoryObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements CategoryAPIRequestedContractObjectTypeFieldResolverInterface
{
    use WithLimitFieldArgResolverTrait;

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?TaxonomyTaxonomiesFilterInputObjectTypeResolver $taxonomyTaxonomiesFilterInputObjectTypeResolver = null;
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
    final public function setTaxonomyTaxonomiesFilterInputObjectTypeResolver(TaxonomyTaxonomiesFilterInputObjectTypeResolver $taxonomyTaxonomiesFilterInputObjectTypeResolver): void
    {
        $this->taxonomyTaxonomiesFilterInputObjectTypeResolver = $taxonomyTaxonomiesFilterInputObjectTypeResolver;
    }
    final protected function getTaxonomyTaxonomiesFilterInputObjectTypeResolver(): TaxonomyTaxonomiesFilterInputObjectTypeResolver
    {
        return $this->taxonomyTaxonomiesFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(TaxonomyTaxonomiesFilterInputObjectTypeResolver::class);
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
            'children',
            'childCount',
            'childNames',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'children' => $this->getCategoryTypeResolver(),
            'childCount' => $this->getIntScalarTypeResolver(),
            'childNames' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'childCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'children',
            'childNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'children' => $this->__('Child categories', 'categories'),
            'childCount' => $this->__('Number of child categories', 'categories'),
            'childNames' => $this->__('Names of the child categories', 'categories'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'children',
            'childNames' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getTaxonomyTaxonomiesFilterInputObjectTypeResolver(),
                    'pagination' => $this->getCategoryPaginationInputObjectTypeResolver(),
                    'sort' => $this->getTaxonomySortInputObjectTypeResolver(),
                ]
            ),
            'childCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getTaxonomyTaxonomiesFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        $category = $object;
        $categoryTypeAPI = $this->getCategoryTypeAPI();
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs),
            [
                'parent-id' => $objectTypeResolver->getID($category),
            ]
        );
        switch ($fieldName) {
            case 'children':
                return $categoryTypeAPI->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'childNames':
                return $categoryTypeAPI->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'childCount':
                return $categoryTypeAPI->getCategoryCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}
