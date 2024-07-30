<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class CategoryUpdateInputObjectTypeResolver extends AbstractCreateOrUpdateCategoryInputObjectTypeResolver implements UpdateCategoryInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CategoryUpdateInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}
