<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\ModuleContracts\CategoryAPIRequestedContractObjectTypeFieldResolverInterface;
use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CategoryPaginationInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomyTaxonomiesFilterInputObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractChildCategoryObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements CategoryAPIRequestedContractObjectTypeFieldResolverInterface
{
    use WithLimitFieldArgResolverTrait;

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?TaxonomyTaxonomiesFilterInputObjectTypeResolver $taxonomyTaxonomiesFilterInputObjectTypeResolver = null;
    private ?CategoryPaginationInputObjectTypeResolver $categoryPaginationInputObjectTypeResolver = null;
    private ?TaxonomySortInputObjectTypeResolver $taxonomySortInputObjectTypeResolver = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final protected function getTaxonomyTaxonomiesFilterInputObjectTypeResolver(): TaxonomyTaxonomiesFilterInputObjectTypeResolver
    {
        if ($this->taxonomyTaxonomiesFilterInputObjectTypeResolver === null) {
            /** @var TaxonomyTaxonomiesFilterInputObjectTypeResolver */
            $taxonomyTaxonomiesFilterInputObjectTypeResolver = $this->instanceManager->getInstance(TaxonomyTaxonomiesFilterInputObjectTypeResolver::class);
            $this->taxonomyTaxonomiesFilterInputObjectTypeResolver = $taxonomyTaxonomiesFilterInputObjectTypeResolver;
        }
        return $this->taxonomyTaxonomiesFilterInputObjectTypeResolver;
    }
    final protected function getCategoryPaginationInputObjectTypeResolver(): CategoryPaginationInputObjectTypeResolver
    {
        if ($this->categoryPaginationInputObjectTypeResolver === null) {
            /** @var CategoryPaginationInputObjectTypeResolver */
            $categoryPaginationInputObjectTypeResolver = $this->instanceManager->getInstance(CategoryPaginationInputObjectTypeResolver::class);
            $this->categoryPaginationInputObjectTypeResolver = $categoryPaginationInputObjectTypeResolver;
        }
        return $this->categoryPaginationInputObjectTypeResolver;
    }
    final protected function getTaxonomySortInputObjectTypeResolver(): TaxonomySortInputObjectTypeResolver
    {
        if ($this->taxonomySortInputObjectTypeResolver === null) {
            /** @var TaxonomySortInputObjectTypeResolver */
            $taxonomySortInputObjectTypeResolver = $this->instanceManager->getInstance(TaxonomySortInputObjectTypeResolver::class);
            $this->taxonomySortInputObjectTypeResolver = $taxonomySortInputObjectTypeResolver;
        }
        return $this->taxonomySortInputObjectTypeResolver;
    }

    /**
     * @return string[]
     */
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

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $category = $object;
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor),
            [
                'parent-id' => $objectTypeResolver->getID($category),
            ]
        );
        switch ($fieldDataAccessor->getFieldName()) {
            case 'children':
                return $this->getCategoryTypeAPI()->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'childNames':
                return $this->getCategoryTypeAPI()->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'childCount':
                return $this->getCategoryTypeAPI()->getCategoryCount($query);
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
}
