<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\Hooks;

use PoPCMSSchema\TagMetaMutations\Hooks\AbstractTagMetaMutationResolverHookSet;
use PoPCMSSchema\CustomPostTagMutations\Constants\GenericTagCRUDHookNames;

class GenericTagMetaMutationResolverHookSet extends AbstractTagMetaMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return GenericTagCRUDHookNames::VALIDATE_CREATE;
    }
    protected function getValidateUpdateHookName(): ?string
    {
        return GenericTagCRUDHookNames::VALIDATE_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return GenericTagCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
