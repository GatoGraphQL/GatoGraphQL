<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

class RootSetCategoryTermMetaInputObjectTypeResolver extends AbstractSetCategoryTermMetaInputObjectTypeResolver implements SetCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootSetCategoryMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
