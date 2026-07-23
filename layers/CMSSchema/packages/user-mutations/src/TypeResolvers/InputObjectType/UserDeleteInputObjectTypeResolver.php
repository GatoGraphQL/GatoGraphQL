<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType;

class UserDeleteInputObjectTypeResolver extends AbstractDeleteUserInputObjectTypeResolver
{
    protected function addIDInputField(): bool
    {
        return false;
    }

    public function getTypeName(): string
    {
        return 'UserDeleteInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a user (nested mutations)', 'gatographql');
    }
}
