<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PageChildrenFilterInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PagePaginationInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class PageObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?PageTypeAPIInterface $pageTypeAPI = null;
    private ?PageChildrenFilterInputObjectTypeResolver $pageChildrenFilterInputObjectTypeResolver = null;
    private ?PagePaginationInputObjectTypeResolver $pagePaginationInputObjectTypeResolver = null;
    private ?CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver = null;

    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
    }
    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        if ($this->pageTypeAPI === null) {
            /** @var PageTypeAPIInterface */
            $pageTypeAPI = $this->instanceManager->getInstance(PageTypeAPIInterface::class);
            $this->pageTypeAPI = $pageTypeAPI;
        }
        return $this->pageTypeAPI;
    }
    final protected function getPageChildrenFilterInputObjectTypeResolver(): PageChildrenFilterInputObjectTypeResolver
    {
        if ($this->pageChildrenFilterInputObjectTypeResolver === null) {
            /** @var PageChildrenFilterInputObjectTypeResolver */
            $pageChildrenFilterInputObjectTypeResolver = $this->instanceManager->getInstance(PageChildrenFilterInputObjectTypeResolver::class);
            $this->pageChildrenFilterInputObjectTypeResolver = $pageChildrenFilterInputObjectTypeResolver;
        }
        return $this->pageChildrenFilterInputObjectTypeResolver;
    }
    final protected function getPagePaginationInputObjectTypeResolver(): PagePaginationInputObjectTypeResolver
    {
        if ($this->pagePaginationInputObjectTypeResolver === null) {
            /** @var PagePaginationInputObjectTypeResolver */
            $pagePaginationInputObjectTypeResolver = $this->instanceManager->getInstance(PagePaginationInputObjectTypeResolver::class);
            $this->pagePaginationInputObjectTypeResolver = $pagePaginationInputObjectTypeResolver;
        }
        return $this->pagePaginationInputObjectTypeResolver;
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
