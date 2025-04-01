<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\Hooks;

use PoPCMSSchema\CustomPostMetaMutations\Hooks\AbstractCustomPostMetaMutationResolverHookSet;
use PoPCMSSchema\CustomPostMutations\Constants\GenericCustomPostCRUDHookNames;

class GenericCustomPostMetaMutationResolverHookSet extends AbstractCustomPostMetaMutationResolverHookSet
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
