<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\PostCategoryMutations\MutationResolvers\SetCategoriesOnPostMutationResolver;

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

    protected function getEntityName(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('post', 'post-category-mutations');
    }
}
