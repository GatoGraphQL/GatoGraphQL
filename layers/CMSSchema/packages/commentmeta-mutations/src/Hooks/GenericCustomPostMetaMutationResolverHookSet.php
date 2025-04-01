<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\Hooks;

use PoPCMSSchema\CommentMetaMutations\Hooks\AbstractCommentMetaMutationResolverHookSet;
use PoPCMSSchema\CommentMutations\Constants\GenericCommentCRUDHookNames;

class GenericCommentMetaMutationResolverHookSet extends AbstractCommentMetaMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return GenericCommentCRUDHookNames::VALIDATE_CREATE;
    }
    protected function getValidateUpdateHookName(): string
    {
        return GenericCommentCRUDHookNames::VALIDATE_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return GenericCommentCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
