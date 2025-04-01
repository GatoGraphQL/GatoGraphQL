<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType;

class UserAddMetaInputObjectTypeResolver extends AbstractAddUserMetaInputObjectTypeResolver implements AddUserMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'UserAddMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
