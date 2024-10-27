<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\ComponentProcessors\CategoryFilterInputContainerComponentProcessor;
use PoPCMSSchema\Categories\FieldResolvers\ObjectType\AbstractCustomPostQueryableObjectTypeFieldResolver;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCustomPostQueryableObjectTypeFieldResolver extends AbstractCustomPostQueryableObjectTypeFieldResolver
{
    private ?QueryableCategoryTypeAPIInterface $queryableCategoryTypeAPI = null;
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;

    final protected function getQueryableCategoryTypeAPI(): QueryableCategoryTypeAPIInterface
    {
        if ($this->queryableCategoryTypeAPI === null) {
            /** @var QueryableCategoryTypeAPIInterface */
            $queryableCategoryTypeAPI = $this->instanceManager->getInstance(QueryableCategoryTypeAPIInterface::class);
            $this->queryableCategoryTypeAPI = $queryableCategoryTypeAPI;
        }
        return $this->queryableCategoryTypeAPI;
    }
    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        if ($this->genericCategoryObjectTypeResolver === null) {
            /** @var GenericCategoryObjectTypeResolver */
            $genericCategoryObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
            $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
        }
        return $this->genericCategoryObjectTypeResolver;
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

    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        return match ($fieldName) {
            'categories',
            'categoryNames',
            'categoryCount'
                => new Component(
                    CategoryFilterInputContainerComponentProcessor::class,
                    CategoryFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GENERICCATEGORIES
                ),
            default
                => parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName),
        };
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
