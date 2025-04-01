<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

class CategoryTermAddMetaInputObjectTypeResolver extends AbstractAddCategoryTermMetaInputObjectTypeResolver implements AddCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CategoryAddMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
