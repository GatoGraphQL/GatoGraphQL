<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoP\Translation\TranslationAPIInterface;

trait SetCategoriesOnCustomPostObjectTypeFieldResolverTrait
{
    abstract protected function getTranslationAPI(): TranslationAPIInterface;

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'custompost-category-mutations');
    }
}
