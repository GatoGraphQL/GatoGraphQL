<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType;

class RootUpdateUserInputObjectTypeResolver extends AbstractUpdateUserInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateUserInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a user', 'gatographql');
    }
}
