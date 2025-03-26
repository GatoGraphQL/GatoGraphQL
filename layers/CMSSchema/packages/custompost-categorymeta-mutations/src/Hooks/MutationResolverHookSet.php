<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\Hooks;

use PoPCMSSchema\CustomPostCategoryMetaMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\CustomPostCategoryMutations\Constants\GenericCategoryCRUDHookNames;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
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
