<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType;

class UserUpdateMetaInputObjectTypeResolver extends AbstractUpdateUserMetaInputObjectTypeResolver implements UpdateUserMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'UserUpdateMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
