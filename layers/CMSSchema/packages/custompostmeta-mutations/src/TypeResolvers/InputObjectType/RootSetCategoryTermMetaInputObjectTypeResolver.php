<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

class RootSetCustomPostMetaInputObjectTypeResolver extends AbstractSetCustomPostMetaInputObjectTypeResolver implements SetCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootSetCategoryMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
