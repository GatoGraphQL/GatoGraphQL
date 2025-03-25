<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

class RootSetMetaOnCategoryInputObjectTypeResolver extends AbstractSetCategoriesOnGenericCustomPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetMetaOnCategoryInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }
}
