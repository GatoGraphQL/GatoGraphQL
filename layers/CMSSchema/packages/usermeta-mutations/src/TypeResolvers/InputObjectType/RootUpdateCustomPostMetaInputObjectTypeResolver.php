<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType;

class RootUpdateUserMetaInputObjectTypeResolver extends AbstractUpdateUserMetaInputObjectTypeResolver implements UpdateUserMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateUserMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
