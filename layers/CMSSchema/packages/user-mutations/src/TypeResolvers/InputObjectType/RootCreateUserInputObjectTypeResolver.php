<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType;

class RootCreateUserInputObjectTypeResolver extends AbstractCreateUserInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateUserInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to create a user', 'gatographql');
    }
}
