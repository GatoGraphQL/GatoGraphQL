<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\Hooks;

use PoPCMSSchema\CustomPostMutations\Constants\GenericCustomPostCRUDHookNames;
use PoPCMSSchema\CustomPostTagMutations\Hooks\AbstractMutationResolverHookSet;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return GenericCustomPostCRUDHookNames::VALIDATE_CREATE;
    }
    protected function getValidateUpdateHookName(): string
    {
        return GenericCustomPostCRUDHookNames::VALIDATE_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return GenericCustomPostCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
