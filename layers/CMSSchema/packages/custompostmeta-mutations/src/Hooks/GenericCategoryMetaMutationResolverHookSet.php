<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\Hooks;

use PoPCMSSchema\CategoryMetaMutations\Hooks\AbstractCategoryMetaMutationResolverHookSet;
use PoPCMSSchema\CustomPostMutations\Constants\GenericCategoryCRUDHookNames;

class GenericCategoryMetaMutationResolverHookSet extends AbstractCategoryMetaMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return GenericCategoryCRUDHookNames::VALIDATE_CREATE;
    }
    protected function getValidateUpdateHookName(): string
    {
        return GenericCategoryCRUDHookNames::VALIDATE_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return GenericCategoryCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
