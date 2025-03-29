<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\Hooks;

use PoPCMSSchema\CategoryMetaMutations\Hooks\AbstractCategoryMetaMutationResolverHookSet;
use PoPCMSSchema\PostCategoryMutations\Constants\PostCategoryCRUDHookNames;

class PostCategoryMetaMutationResolverHookSet extends AbstractCategoryMetaMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return PostCategoryCRUDHookNames::VALIDATE_CREATE;
    }
    protected function getValidateUpdateHookName(): string
    {
        return PostCategoryCRUDHookNames::VALIDATE_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return PostCategoryCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
