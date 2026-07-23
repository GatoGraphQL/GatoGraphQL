<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\Hooks;

use PoPCMSSchema\UserMetaMutations\Hooks\AbstractUserMetaMutationResolverHookSet;
use PoPCMSSchema\UserMutations\Constants\UserCRUDHookNames;

class UserMetaMutationResolverHookSet extends AbstractUserMetaMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return UserCRUDHookNames::VALIDATE_CREATE_USER;
    }
    protected function getValidateUpdateHookName(): ?string
    {
        return UserCRUDHookNames::VALIDATE_UPDATE_USER;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return UserCRUDHookNames::CREATE_OR_UPDATE_USER;
    }
}
