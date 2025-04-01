<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType;

class RootSetUserMetaInputObjectTypeResolver extends AbstractSetUserMetaInputObjectTypeResolver implements SetUserMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootSetUserMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
