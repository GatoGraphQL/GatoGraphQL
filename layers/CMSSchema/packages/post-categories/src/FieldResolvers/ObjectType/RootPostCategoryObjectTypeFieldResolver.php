<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CategoryPaginationInputObjectTypeResolver;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\PostCategories\TypeResolvers\EnumType\PostCategoryTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\PostCategories\TypeResolvers\InputObjectType\PostCategoryByOneofInputObjectTypeResolver;
use PoPCMSSchema\PostCategories\TypeResolvers\InputObjectType\RootPostCategoriesFilterInputObjectTypeResolver;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver;
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
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class RootPostCategoryObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;
    private ?PostCategoryByOneofInputObjectTypeResolver $postCategoryByOneofInputObjectTypeResolver = null;
    private ?RootPostCategoriesFilterInputObjectTypeResolver $rootPostCategoriesFilterInputObjectTypeResolver = null;
    private ?CategoryPaginationInputObjectTypeResolver $categoryPaginationInputObjectTypeResolver = null;
    private ?TaxonomySortInputObjectTypeResolver $taxonomySortInputObjectTypeResolver = null;
    private ?PostCategoryTaxonomyEnumStringScalarTypeResolver $postCategoryTaxonomyEnumStringScalarTypeResolver = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
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
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final public function setPostCategoryObjectTypeResolver(PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver): void
    {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }
    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        if ($this->postCategoryObjectTypeResolver === null) {
            /** @var PostCategoryObjectTypeResolver */
            $postCategoryObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
            $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        }
        return $this->postCategoryObjectTypeResolver;
    }
    final public function setPostCategoryTypeAPI(PostCategoryTypeAPIInterface $postCategoryTypeAPI): void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        if ($this->postCategoryTypeAPI === null) {
            /** @var PostCategoryTypeAPIInterface */
            $postCategoryTypeAPI = $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
            $this->postCategoryTypeAPI = $postCategoryTypeAPI;
        }
        return $this->postCategoryTypeAPI;
    }
    final public function setPostCategoryByOneofInputObjectTypeResolver(PostCategoryByOneofInputObjectTypeResolver $postCategoryByOneofInputObjectTypeResolver): void
    {
        $this->postCategoryByOneofInputObjectTypeResolver = $postCategoryByOneofInputObjectTypeResolver;
    }
    final protected function getPostCategoryByOneofInputObjectTypeResolver(): PostCategoryByOneofInputObjectTypeResolver
    {
        if ($this->postCategoryByOneofInputObjectTypeResolver === null) {
            /** @var PostCategoryByOneofInputObjectTypeResolver */
            $postCategoryByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryByOneofInputObjectTypeResolver::class);
            $this->postCategoryByOneofInputObjectTypeResolver = $postCategoryByOneofInputObjectTypeResolver;
        }
        return $this->postCategoryByOneofInputObjectTypeResolver;
    }
    final public function setRootPostCategoriesFilterInputObjectTypeResolver(RootPostCategoriesFilterInputObjectTypeResolver $rootPostCategoriesFilterInputObjectTypeResolver): void
    {
        $this->rootPostCategoriesFilterInputObjectTypeResolver = $rootPostCategoriesFilterInputObjectTypeResolver;
    }
    final protected function getRootPostCategoriesFilterInputObjectTypeResolver(): RootPostCategoriesFilterInputObjectTypeResolver
    {
        if ($this->rootPostCategoriesFilterInputObjectTypeResolver === null) {
            /** @var RootPostCategoriesFilterInputObjectTypeResolver */
            $rootPostCategoriesFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootPostCategoriesFilterInputObjectTypeResolver::class);
            $this->rootPostCategoriesFilterInputObjectTypeResolver = $rootPostCategoriesFilterInputObjectTypeResolver;
        }
        return $this->rootPostCategoriesFilterInputObjectTypeResolver;
    }
    final public function setCategoryPaginationInputObjectTypeResolver(CategoryPaginationInputObjectTypeResolver $categoryPaginationInputObjectTypeResolver): void
    {
        $this->categoryPaginationInputObjectTypeResolver = $categoryPaginationInputObjectTypeResolver;
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
    final public function setTaxonomySortInputObjectTypeResolver(TaxonomySortInputObjectTypeResolver $taxonomySortInputObjectTypeResolver): void
    {
        $this->taxonomySortInputObjectTypeResolver = $taxonomySortInputObjectTypeResolver;
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
    final public function setPostCategoryTaxonomyEnumStringScalarTypeResolver(PostCategoryTaxonomyEnumStringScalarTypeResolver $postCategoryTaxonomyEnumStringScalarTypeResolver): void
    {
        $this->postCategoryTaxonomyEnumStringScalarTypeResolver = $postCategoryTaxonomyEnumStringScalarTypeResolver;
    }
    final protected function getPostCategoryTaxonomyEnumStringScalarTypeResolver(): PostCategoryTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->postCategoryTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var PostCategoryTaxonomyEnumStringScalarTypeResolver */
            $postCategoryTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(PostCategoryTaxonomyEnumStringScalarTypeResolver::class);
            $this->postCategoryTaxonomyEnumStringScalarTypeResolver = $postCategoryTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->postCategoryTaxonomyEnumStringScalarTypeResolver;
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
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'postCategory',
            'postCategories',
            'postCategoryCount',
            'postCategoryNames',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'postCategory',
            'postCategories'
                => $this->getPostCategoryObjectTypeResolver(),
            'postCategoryCount'
                => $this->getIntScalarTypeResolver(),
            'postCategoryNames'
                => $this->getStringScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'postCategoryCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'postCategories',
            'postCategoryNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'postCategory' => $this->__('Retrieve a single post category', 'post-categories'),
            'postCategories' => $this->__('Post categories', 'post-categories'),
            'postCategoryCount' => $this->__('Number of post categories', 'post-categories'),
            'postCategoryNames' => $this->__('Names of the post categories', 'post-categories'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        $commonFieldArgNameTypeResolvers = [
            'taxonomy' => $this->getPostCategoryTaxonomyEnumStringScalarTypeResolver(),
        ];
        return match ($fieldName) {
            'postCategory' => array_merge(
                $fieldArgNameTypeResolvers,
                $commonFieldArgNameTypeResolvers,
                [
                    'by' => $this->getPostCategoryByOneofInputObjectTypeResolver(),
                ]
            ),
            'postCategories',
            'postCategoryNames' => array_merge(
                $fieldArgNameTypeResolvers,
                $commonFieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootPostCategoriesFilterInputObjectTypeResolver(),
                    'pagination' => $this->getCategoryPaginationInputObjectTypeResolver(),
                    'sort' => $this->getTaxonomySortInputObjectTypeResolver(),
                ]
            ),
            'postCategoryCount' => array_merge(
                $fieldArgNameTypeResolvers,
                $commonFieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootPostCategoriesFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['postCategory' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        if ($fieldArgName === 'taxonomy') {
            return $this->__('Taxonomy of the post category', 'post-categories');
        }
        return match ([$fieldName => $fieldArgName]) {
            ['postCategory' => 'by'] => $this->__('Parameter by which to select the post category', 'post-categories'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * @return array<string,mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        $query = [];

        /** @var string|null */
        $postCategoryTaxonomy = $fieldDataAccessor->getValue('taxonomy');
        if ($postCategoryTaxonomy !== null) {
            $query['taxonomy'] = $postCategoryTaxonomy;
        }

        return $query;
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor),
            $this->getQuery($objectTypeResolver, $object, $fieldDataAccessor)
        );
        switch ($fieldDataAccessor->getFieldName()) {
            case 'postCategory':
                if ($categories = $this->getPostCategoryTypeAPI()->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $categories[0];
                }
                return null;
            case 'postCategories':
                return $this->getPostCategoryTypeAPI()->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'postCategoryNames':
                return $this->getPostCategoryTypeAPI()->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'postCategoryCount':
                return $this->getPostCategoryTypeAPI()->getCategoryCount($query);
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
