<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType;

class GenericCustomPostSetCategoriesInputObjectTypeResolver extends AbstractSetCategoriesOnGenericCustomPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCustomPostSetCategoriesInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}
