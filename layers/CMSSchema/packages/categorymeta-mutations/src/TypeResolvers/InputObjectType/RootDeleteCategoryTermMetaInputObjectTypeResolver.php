<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

class RootDeleteCategoryTermMetaInputObjectTypeResolver extends AbstractDeleteCategoryTermMetaInputObjectTypeResolver implements DeleteCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootDeleteCategoryMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
