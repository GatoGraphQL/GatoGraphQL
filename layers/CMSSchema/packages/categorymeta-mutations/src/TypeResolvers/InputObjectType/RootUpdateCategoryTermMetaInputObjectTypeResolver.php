<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

class RootUpdateCategoryTermMetaInputObjectTypeResolver extends AbstractUpdateCategoryTermMetaInputObjectTypeResolver implements UpdateCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateCategoryMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
