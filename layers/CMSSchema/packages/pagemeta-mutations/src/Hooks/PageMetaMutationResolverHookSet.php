<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\Hooks;

use PoPCMSSchema\CustomPostMetaMutations\Hooks\AbstractCustomPostMetaMutationResolverHookSet;
use PoPCMSSchema\PageMutations\Constants\PageCRUDHookNames;

class PageMetaMutationResolverHookSet extends AbstractCustomPostMetaMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return PageCRUDHookNames::VALIDATE_CREATE;
    }
    protected function getValidateUpdateHookName(): ?string
    {
        return PageCRUDHookNames::VALIDATE_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return PageCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
