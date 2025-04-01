<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\Hooks;

use PoPCMSSchema\UserMetaMutations\Hooks\AbstractUserMetaMutationResolverHookSet;
use PoPCMSSchema\UserMutations\Constants\UserCRUDHookNames;

class UserMetaMutationResolverHookSet extends AbstractUserMetaMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return UserCRUDHookNames::VALIDATE_CREATE;
    }
    protected function getValidateUpdateHookName(): ?string
    {
        return UserCRUDHookNames::VALIDATE_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return UserCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
