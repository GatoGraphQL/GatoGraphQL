<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

class RootAddCategoryTermMetaInputObjectTypeResolver extends AbstractAddCategoryTermMetaInputObjectTypeResolver implements AddCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootAddCategoryMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
