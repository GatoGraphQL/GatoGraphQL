<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

class RootSetCategoriesOnPostInputObjectTypeResolver extends AbstractSetCategoriesOnPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetCategoriesOnPostInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }
}
