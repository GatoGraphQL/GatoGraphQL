<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\Hooks;

use PoPCMSSchema\CustomPostCategoryMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\PostMutations\Constants\PostCRUDHookNames;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return PostCRUDHookNames::VALIDATE_CREATE;
    }
    protected function getValidateUpdateHookName(): string
    {
        return PostCRUDHookNames::VALIDATE_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return PostCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
