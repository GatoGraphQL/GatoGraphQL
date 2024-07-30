<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class RootCreateGenericCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryTermInputObjectTypeResolver implements CreateCategoryInputObjectTypeResolverInterface
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
