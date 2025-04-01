<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType;

class RootDeleteUserMetaInputObjectTypeResolver extends AbstractDeleteUserMetaInputObjectTypeResolver implements DeleteUserMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootDeleteUserMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
