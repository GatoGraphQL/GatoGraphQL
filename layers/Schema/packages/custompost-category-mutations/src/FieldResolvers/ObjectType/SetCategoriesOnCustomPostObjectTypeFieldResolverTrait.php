<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;

trait SetCategoriesOnCustomPostObjectTypeFieldResolverTrait
{
    abstract protected function getCustomPostTypeResolverClass(): string;
    abstract protected function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;
    abstract protected function getTypeMutationResolverClass(): string;

    protected function getEntityName(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('custom post', 'custompost-category-mutations');
    }
}
