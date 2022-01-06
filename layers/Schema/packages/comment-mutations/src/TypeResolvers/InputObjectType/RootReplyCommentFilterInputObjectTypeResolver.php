<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\TypeResolvers\InputObjectType;

class RootReplyCommentFilterInputObjectTypeResolver extends AbstractAddCommentToCustomPostFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootReplyCommentFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to add a comment to a custom post', 'comment-mutations');
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }

    protected function addParentCommentInputField(): bool
    {
        return true;
    }

    protected function isParentCommentInputFieldMandatory(): bool
    {
        return true;
    }
}
