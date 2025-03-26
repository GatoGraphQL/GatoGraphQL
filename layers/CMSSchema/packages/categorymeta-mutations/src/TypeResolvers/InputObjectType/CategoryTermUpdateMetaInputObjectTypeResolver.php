<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

class CategoryTermUpdateMetaInputObjectTypeResolver extends AbstractUpdateCategoryTermMetaInputObjectTypeResolver implements UpdateCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CategoryUpdateMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
