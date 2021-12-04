<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

class RootSetCategoriesOnCustomPostFilterInputObjectTypeResolver extends AbstractSetCategoriesOnPostFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetCategoriesOnCustomPostFilterInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }
}
