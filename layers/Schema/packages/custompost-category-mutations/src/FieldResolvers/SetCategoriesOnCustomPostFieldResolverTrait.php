<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;

trait SetCategoriesOnCustomPostFieldResolverTrait
{
    abstract protected function getTypeResolverClass(): string;
    abstract protected function getCategoryTypeResolverClass(): string;
    abstract protected function getTypeMutationResolverClass(): string;

    protected function getEntityName(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('custom post', 'custompost-category-mutations');
    }
}
