<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\AbstractSetCategoriesOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;

abstract class AbstractSetCategoriesOnGenericCustomPostInputObjectTypeResolver extends AbstractSetCategoriesOnCustomPostInputObjectTypeResolver
{
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;

    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        if ($this->genericCategoryObjectTypeResolver === null) {
            /** @var GenericCategoryObjectTypeResolver */
            $genericCategoryObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
            $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
        }
        return $this->genericCategoryObjectTypeResolver;
    }

    protected function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->getGenericCategoryObjectTypeResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'postcategory-mutations');
    }

    protected function addTaxonomyInputField(): bool
    {
        return true;
    }
}
