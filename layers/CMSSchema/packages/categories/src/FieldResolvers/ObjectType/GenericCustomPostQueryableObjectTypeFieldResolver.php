<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\FieldResolvers\ObjectType\AbstractCustomPostQueryableObjectTypeFieldResolver;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\GenericCustomPostCategoriesFilterInputObjectTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCustomPostQueryableObjectTypeFieldResolver extends AbstractCustomPostQueryableObjectTypeFieldResolver
{
    private ?QueryableCategoryTypeAPIInterface $queryableCategoryTypeAPI = null;
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;
    private ?GenericCustomPostCategoriesFilterInputObjectTypeResolver $genericCustomPostCategoriesFilterInputObjectTypeResolver = null;

    final public function setQueryableCategoryTypeAPI(QueryableCategoryTypeAPIInterface $queryableCategoryTypeAPI): void
    {
        $this->queryableCategoryTypeAPI = $queryableCategoryTypeAPI;
    }
    final protected function getQueryableCategoryTypeAPI(): QueryableCategoryTypeAPIInterface
    {
        /** @var QueryableCategoryTypeAPIInterface */
        return $this->queryableCategoryTypeAPI ??= $this->instanceManager->getInstance(QueryableCategoryTypeAPIInterface::class);
    }
    final public function setGenericCategoryObjectTypeResolver(GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver): void
    {
        $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
    }
    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        /** @var GenericCategoryObjectTypeResolver */
        return $this->genericCategoryObjectTypeResolver ??= $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
    }
    final public function setGenericCustomPostCategoriesFilterInputObjectTypeResolver(GenericCustomPostCategoriesFilterInputObjectTypeResolver $genericCustomPostCategoriesFilterInputObjectTypeResolver): void
    {
        $this->genericCustomPostCategoriesFilterInputObjectTypeResolver = $genericCustomPostCategoriesFilterInputObjectTypeResolver;
    }
    final protected function getGenericCustomPostCategoriesFilterInputObjectTypeResolver(): GenericCustomPostCategoriesFilterInputObjectTypeResolver
    {
        /** @var GenericCustomPostCategoriesFilterInputObjectTypeResolver */
        return $this->genericCustomPostCategoriesFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(GenericCustomPostCategoriesFilterInputObjectTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCustomPostObjectTypeResolver::class,
        ];
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        /**
         * Add the "taxonomy" mandatory fieldArg
         */
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        if (in_array($fieldName, [
            'categories',
            'categoryNames',
            'categoryCount',
        ])) {
            $fieldArgNameTypeResolvers['filter'] = $this->getGenericCustomPostCategoriesFilterInputObjectTypeResolver();
        }
        return $fieldArgNameTypeResolvers;
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'categories' => $this->__('Categories added to this custom post', 'pop-post-categories'),
            'categoryCount' => $this->__('Number of categories added to this custom post', 'pop-post-categories'),
            'categoryNames' => $this->__('Names of the categories added to this custom post', 'pop-post-categories'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getQueryableCategoryTypeAPI();
    }

    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->getGenericCategoryObjectTypeResolver();
    }
}
