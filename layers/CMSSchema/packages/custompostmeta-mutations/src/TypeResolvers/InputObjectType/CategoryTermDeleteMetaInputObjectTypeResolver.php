<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

class CategoryTermDeleteMetaInputObjectTypeResolver extends AbstractDeleteCategoryTermMetaInputObjectTypeResolver implements DeleteCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CategoryDeleteMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
