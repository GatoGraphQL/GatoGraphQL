<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType;

class RootDeleteUserInputObjectTypeResolver extends AbstractDeleteUserInputObjectTypeResolver
{
    protected function addIDInputField(): bool
    {
        return true;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteUserInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a user', 'gatographql');
    }
}
