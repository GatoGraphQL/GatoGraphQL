<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType;

class UserSetMetaInputObjectTypeResolver extends AbstractSetUserMetaInputObjectTypeResolver implements SetUserMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'UserSetMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
