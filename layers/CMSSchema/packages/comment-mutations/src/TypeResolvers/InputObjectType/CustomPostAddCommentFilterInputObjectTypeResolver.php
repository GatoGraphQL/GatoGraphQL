<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType;

class CustomPostAddCommentFilterInputObjectTypeResolver extends AbstractAddCommentToCustomPostFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostAddCommentFilterInput';
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
        return false;
    }
}
