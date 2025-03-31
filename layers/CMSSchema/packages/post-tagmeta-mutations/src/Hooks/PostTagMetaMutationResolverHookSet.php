<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\Hooks;

use PoPCMSSchema\TagMetaMutations\Hooks\AbstractTagMetaMutationResolverHookSet;
use PoPCMSSchema\PostTagMutations\Constants\PostTagCRUDHookNames;

class PostTagMetaMutationResolverHookSet extends AbstractTagMetaMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return PostTagCRUDHookNames::VALIDATE_CREATE;
    }
    protected function getValidateUpdateHookName(): string
    {
        return PostTagCRUDHookNames::VALIDATE_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return PostTagCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
