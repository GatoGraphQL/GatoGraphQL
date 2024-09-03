<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\FieldResolvers\InterfaceType\CategoryInterfaceTypeFieldResolver;
use PoPCMSSchema\Categories\ModuleContracts\CategoryAPIRequestedContractObjectTypeFieldResolverInterface;
use PoPCMSSchema\Categories\TypeAPIs\UniversalCategoryTypeAPIInterface;
use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractCategoryObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver implements CategoryAPIRequestedContractObjectTypeFieldResolverInterface
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver = null;
    private ?CategoryInterfaceTypeFieldResolver $categoryInterfaceTypeFieldResolver = null;
    private ?UniversalCategoryTypeAPIInterface $universalCategoryTypeAPI = null;

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
    final public function setQueryableInterfaceTypeFieldResolver(QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver): void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    final protected function getQueryableInterfaceTypeFieldResolver(): QueryableInterfaceTypeFieldResolver
    {
        if ($this->queryableInterfaceTypeFieldResolver === null) {
            /** @var QueryableInterfaceTypeFieldResolver */
            $queryableInterfaceTypeFieldResolver = $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
            $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
        }
        return $this->queryableInterfaceTypeFieldResolver;
    }
    final public function setCategoryInterfaceTypeFieldResolver(CategoryInterfaceTypeFieldResolver $categoryInterfaceTypeFieldResolver): void
    {
        $this->categoryInterfaceTypeFieldResolver = $categoryInterfaceTypeFieldResolver;
    }
    final protected function getCategoryInterfaceTypeFieldResolver(): CategoryInterfaceTypeFieldResolver
    {
        if ($this->categoryInterfaceTypeFieldResolver === null) {
            /** @var CategoryInterfaceTypeFieldResolver */
            $categoryInterfaceTypeFieldResolver = $this->instanceManager->getInstance(CategoryInterfaceTypeFieldResolver::class);
            $this->categoryInterfaceTypeFieldResolver = $categoryInterfaceTypeFieldResolver;
        }
        return $this->categoryInterfaceTypeFieldResolver;
    }
    final public function setUniversalCategoryTypeAPI(UniversalCategoryTypeAPIInterface $universalCategoryTypeAPI): void
    {
        $this->universalCategoryTypeAPI = $universalCategoryTypeAPI;
    }
    final protected function getUniversalCategoryTypeAPI(): UniversalCategoryTypeAPIInterface
    {
        if ($this->universalCategoryTypeAPI === null) {
            /** @var UniversalCategoryTypeAPIInterface */
            $universalCategoryTypeAPI = $this->instanceManager->getInstance(UniversalCategoryTypeAPIInterface::class);
            $this->universalCategoryTypeAPI = $universalCategoryTypeAPI;
        }
        return $this->universalCategoryTypeAPI;
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getQueryableInterfaceTypeFieldResolver(),
            $this->getCategoryInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            // Queryable interface
            'url',
            'urlPath',
            'slug',

            // Category interface
            'name',
            'description',
            'count',
            'slugPath',

            // Own
            'parent',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'parent' => $this->getCategoryTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'url' => $this->__('Category URL', 'pop-categories'),
            'urlPath' => $this->__('Category URL path', 'pop-categories'),
            'slug' => $this->__('Category slug', 'pop-categories'),
            'parent' => $this->__('Parent category (if this category is a child of another one)', 'pop-categories'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $category = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'url':
                /** @var string */
                return $this->getUniversalCategoryTypeAPI()->getCategoryURL($category);

            case 'urlPath':
                /** @var string */
                return $this->getUniversalCategoryTypeAPI()->getCategoryURLPath($category);

            case 'name':
                /** @var string */
                return $this->getUniversalCategoryTypeAPI()->getCategoryName($category);

            case 'slug':
                /** @var string */
                return $this->getUniversalCategoryTypeAPI()->getCategorySlug($category);

            case 'slugPath':
                /** @var string */
                return $this->getUniversalCategoryTypeAPI()->getCategorySlugPath($category);

            case 'description':
                /** @var string */
                return $this->getUniversalCategoryTypeAPI()->getCategoryDescription($category);

            case 'parent':
                return $this->getUniversalCategoryTypeAPI()->getCategoryParentID($category);

            case 'count':
                /** @var int */
                return $this->getUniversalCategoryTypeAPI()->getCategoryItemCount($category);
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
