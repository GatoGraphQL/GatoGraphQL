<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

trait SetCategoriesOnCustomPostObjectTypeFieldResolverTrait
{
    protected function getEntityName(): string
    {
        return $this->getTranslationAPI()->__('custom post', 'custompost-category-mutations');
    }
}
