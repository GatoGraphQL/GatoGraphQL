<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PageChildrenFilterInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PagePaginationInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

class PageObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?PageTypeAPIInterface $pageTypeAPI = null;
    private ?PageChildrenFilterInputObjectTypeResolver $pageChildrenFilterInputObjectTypeResolver = null;
    private ?PagePaginationInputObjectTypeResolver $pagePaginationInputObjectTypeResolver = null;
    private ?CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        /** @var IntScalarTypeResolver */
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setPageObjectTypeResolver(PageObjectTypeResolver $pageObjectTypeResolver): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        /** @var PageObjectTypeResolver */
        return $this->pageObjectTypeResolver ??= $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }
    final public function setPageTypeAPI(PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        /** @var PageTypeAPIInterface */
        return $this->pageTypeAPI ??= $this->instanceManager->getInstance(PageTypeAPIInterface::class);
    }
    final public function setPageChildrenFilterInputObjectTypeResolver(PageChildrenFilterInputObjectTypeResolver $pageChildrenFilterInputObjectTypeResolver): void
    {
        $this->pageChildrenFilterInputObjectTypeResolver = $pageChildrenFilterInputObjectTypeResolver;
    }
    final protected function getPageChildrenFilterInputObjectTypeResolver(): PageChildrenFilterInputObjectTypeResolver
    {
        /** @var PageChildrenFilterInputObjectTypeResolver */
        return $this->pageChildrenFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(PageChildrenFilterInputObjectTypeResolver::class);
    }
    final public function setPagePaginationInputObjectTypeResolver(PagePaginationInputObjectTypeResolver $pagePaginationInputObjectTypeResolver): void
    {
        $this->pagePaginationInputObjectTypeResolver = $pagePaginationInputObjectTypeResolver;
    }
    final protected function getPagePaginationInputObjectTypeResolver(): PagePaginationInputObjectTypeResolver
    {
        /** @var PagePaginationInputObjectTypeResolver */
        return $this->pagePaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(PagePaginationInputObjectTypeResolver::class);
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
            PageObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'parent',
            'children',
            'childCount',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'parent' => $this->__('Parent page', 'pages'),
            'children' => $this->__('Child pages', 'pages'),
            'childCount' => $this->__('Number of child pages', 'pages'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'parent',
            'children'
                => $this->getPageObjectTypeResolver(),
            'childCount'
                => $this->getIntScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'childCount' => SchemaTypeModifiers::NON_NULLABLE,
            'children' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'children' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getPageChildrenFilterInputObjectTypeResolver(),
                    'pagination' => $this->getPagePaginationInputObjectTypeResolver(),
                    'sort' => $this->getCustomPostSortInputObjectTypeResolver(),
                ]
            ),
            'childCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getPageChildrenFilterInputObjectTypeResolver(),
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
        $page = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'parent':
                return $this->getPageTypeAPI()->getParentPageID($page);
        }

        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor),
            [
                'parent-id' => $objectTypeResolver->getID($page),
            ]
        );
        switch ($fieldDataAccessor->getFieldName()) {
            case 'children':
                return $this->getPageTypeAPI()->getPages($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'childCount':
                return $this->getPageTypeAPI()->getPageCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
