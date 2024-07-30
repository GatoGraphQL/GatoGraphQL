<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class RootUpdateCategoryInputObjectTypeResolver extends AbstractCreateOrUpdateCategoryInputObjectTypeResolver implements UpdateCategoryInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateCategoryInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }
}
