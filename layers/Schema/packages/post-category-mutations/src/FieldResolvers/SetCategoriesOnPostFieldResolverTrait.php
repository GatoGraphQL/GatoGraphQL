<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\PostCategories\TypeResolvers\PostCategoryTypeResolver;
use PoPSchema\PostCategoryMutations\MutationResolvers\SetCategoriesOnPostMutationResolver;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;

trait SetCategoriesOnPostFieldResolverTrait
{
    protected function getTypeResolverClass(): string
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
