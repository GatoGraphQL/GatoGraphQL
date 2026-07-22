<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType;

abstract class AbstractCreateUserInputObjectTypeResolver extends AbstractCreateOrUpdateUserInputObjectTypeResolver implements CreateUserInputObjectTypeResolverInterface
{
    protected function addUserInputField(): bool
    {
        return false;
    }

    protected function addUsernameInputField(): bool
    {
        return true;
    }
}
