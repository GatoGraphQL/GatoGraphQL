<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

class PostSetCategoriesFilterInputObjectTypeResolver extends AbstractSetCategoriesOnPostFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostSetCategoriesFilterInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}
