<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\Hooks;

use PoPCMSSchema\CustomPostMetaMutations\Hooks\AbstractCustomPostMetaMutationResolverHookSet;
use PoPCMSSchema\PostMutations\Constants\PostCRUDHookNames;

class PostMetaMutationResolverHookSet extends AbstractCustomPostMetaMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return PostCRUDHookNames::VALIDATE_CREATE;
    }
    protected function getValidateUpdateHookName(): ?string
    {
        return PostCRUDHookNames::VALIDATE_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return PostCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
