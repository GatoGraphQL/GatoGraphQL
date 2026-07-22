<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType;

abstract class AbstractUpdateUserInputObjectTypeResolver extends AbstractCreateOrUpdateUserInputObjectTypeResolver implements UpdateUserInputObjectTypeResolverInterface
{
    protected function addUserInputField(): bool
    {
        return true;
    }

    protected function addUsernameInputField(): bool
    {
        return false;
    }
}
