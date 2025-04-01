<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType;

class RootAddUserMetaInputObjectTypeResolver extends AbstractAddUserMetaInputObjectTypeResolver implements AddUserMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootAddUserMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
