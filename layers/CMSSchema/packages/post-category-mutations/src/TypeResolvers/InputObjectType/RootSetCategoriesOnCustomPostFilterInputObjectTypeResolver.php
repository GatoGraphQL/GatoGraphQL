<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

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
