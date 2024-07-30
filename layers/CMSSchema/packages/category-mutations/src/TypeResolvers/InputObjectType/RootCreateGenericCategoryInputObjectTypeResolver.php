<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class RootCreateGenericCategoryInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryInputObjectTypeResolver implements CreateCategoryInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootCreateCategoryInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}
