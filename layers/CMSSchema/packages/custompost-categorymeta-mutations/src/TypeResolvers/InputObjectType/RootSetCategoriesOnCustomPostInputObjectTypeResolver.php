<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

class RootSetCategoriesOnCustomPostInputObjectTypeResolver extends AbstractSetCategoriesOnGenericCustomPostInputObjectTypeResolver
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
