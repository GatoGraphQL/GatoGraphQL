<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\Hooks;

use PoPCMSSchema\CustomPostCategoryMetaMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\CustomPostCategoryMutations\Constants\GenericCategoryCRUDHookNames;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return GenericCategoryCRUDHookNames::VALIDATE_ADD_META;
    }
    protected function getValidateUpdateHookName(): string
    {
        return GenericCategoryCRUDHookNames::VALIDATE_UPDATE_META;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return GenericCategoryCRUDHookNames::EXECUTE_SET_META;
    }
}
