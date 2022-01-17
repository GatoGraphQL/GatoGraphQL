<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\TypeResolvers\InputObjectType;

class CommentReplyFilterInputObjectTypeResolver extends AbstractAddCommentToCustomPostFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentReplyFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to reply to a comment', 'comment-mutations');
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }

    protected function addParentCommentInputField(): bool
    {
        return false;
    }

    protected function isParentCommentInputFieldMandatory(): bool
    {
        return false;
    }
}
