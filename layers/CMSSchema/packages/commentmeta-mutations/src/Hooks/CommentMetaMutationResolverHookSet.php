<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\Hooks;

use PoPCMSSchema\CommentMetaMutations\Hooks\AbstractCommentMetaMutationResolverHookSet;
use PoPCMSSchema\CommentMutations\Constants\CommentCRUDHookNames;

class CommentMetaMutationResolverHookSet extends AbstractCommentMetaMutationResolverHookSet
{
    protected function getValidateCreateHookName(): string
    {
        return CommentCRUDHookNames::VALIDATE_ADD_COMMENT;
    }
    protected function getValidateUpdateHookName(): ?string
    {
        return null;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return CommentCRUDHookNames::EXECUTE_ADD_COMMENT;
    }
}
