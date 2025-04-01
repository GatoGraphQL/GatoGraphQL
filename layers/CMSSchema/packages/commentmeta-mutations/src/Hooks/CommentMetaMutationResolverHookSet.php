<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\Hooks;

use PoPCMSSchema\CommentMetaMutations\Hooks\AbstractCommentMetaMutationResolverHookSet;
use PoPCMSSchema\CommentMutations\Constants\CommentCRUDHookNames;

class CommentMetaMutationResolverHookSet extends AbstractCommentMetaMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return CommentCRUDHookNames::VALIDATE_CREATE;
    }
    protected function getValidateUpdateHookName(): string
    {
        return CommentCRUDHookNames::VALIDATE_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return CommentCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
