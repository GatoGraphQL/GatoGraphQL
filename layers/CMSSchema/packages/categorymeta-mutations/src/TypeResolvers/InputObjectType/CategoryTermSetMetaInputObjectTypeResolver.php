<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

class CategoryTermSetMetaInputObjectTypeResolver extends AbstractSetCategoryTermMetaInputObjectTypeResolver implements SetCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CategorySetMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
