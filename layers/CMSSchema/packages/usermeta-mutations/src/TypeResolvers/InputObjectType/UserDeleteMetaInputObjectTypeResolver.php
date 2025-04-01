<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType;

class UserDeleteMetaInputObjectTypeResolver extends AbstractDeleteUserMetaInputObjectTypeResolver implements DeleteUserMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'UserDeleteMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
