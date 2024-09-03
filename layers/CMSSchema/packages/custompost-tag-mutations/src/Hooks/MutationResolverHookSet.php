<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\Hooks;

use PoPCMSSchema\CustomPostMutations\Constants\GenericCustomPostCRUDHookNames;
use PoPCMSSchema\CustomPostTagMutations\Hooks\AbstractMutationResolverHookSet;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    protected function getValidateCreateOrUpdateHookName(): string
    {
        return GenericCustomPostCRUDHookNames::VALIDATE_CREATE_OR_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return GenericCustomPostCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
