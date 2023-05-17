<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

class RootSetCategoriesOnCustomPostInputObjectTypeResolver extends AbstractSetCategoriesOnPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetCategoriesOnCustomPostInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }
}
