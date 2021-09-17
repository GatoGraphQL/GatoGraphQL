<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoP\Translation\Facades\TranslationAPIFacade;

trait SetCategoriesOnCustomPostObjectTypeFieldResolverTrait
{
    protected function getEntityName(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('custom post', 'custompost-category-mutations');
    }
}
