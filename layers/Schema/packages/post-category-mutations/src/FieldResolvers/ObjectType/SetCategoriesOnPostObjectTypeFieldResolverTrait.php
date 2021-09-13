<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryTypeResolver;
use PoPSchema\PostCategoryMutations\MutationResolvers\SetCategoriesOnPostMutationResolver;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostTypeResolver;

trait SetCategoriesOnPostObjectTypeFieldResolverTrait
{
    protected function getCustomPostTypeResolverClass(): string
    {
        return PostTypeResolver::class;
    }

    protected function getTypeMutationResolverClass(): string
    {
        return SetCategoriesOnPostMutationResolver::class;
    }

    protected function getCategoryTypeResolverClass(): string
    {
        return PostCategoryTypeResolver::class;
    }

    protected function getEntityName(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('post', 'post-category-mutations');
    }
}
