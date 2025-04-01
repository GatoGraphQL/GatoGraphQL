<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\Hooks;

use PoPCMSSchema\UserMetaMutations\Hooks\AbstractUserMetaMutationResolverHookSet;
use PoPCMSSchema\UserMutations\Constants\GenericUserCRUDHookNames;

class GenericUserMetaMutationResolverHookSet extends AbstractUserMetaMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return GenericUserCRUDHookNames::VALIDATE_CREATE;
    }
    protected function getValidateUpdateHookName(): string
    {
        return GenericUserCRUDHookNames::VALIDATE_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return GenericUserCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
