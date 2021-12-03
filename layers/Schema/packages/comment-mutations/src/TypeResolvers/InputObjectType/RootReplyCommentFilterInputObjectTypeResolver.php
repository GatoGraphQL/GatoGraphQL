<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\TypeResolvers\InputObjectType;

class RootReplyCommentFilterInputObjectTypeResolver extends AbstractAddCommentToCustomPostFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'ReplyCommentFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to add a comment to a custom post', 'comment-mutations');
    }

    protected function addCustomPostInputFields(): bool
    {
        return false;
    }

    protected function addParentInputFields(): bool
    {
        return true;
    }

    protected function isParentCommentMandatory(): bool
    {
        return true;
    }

}
