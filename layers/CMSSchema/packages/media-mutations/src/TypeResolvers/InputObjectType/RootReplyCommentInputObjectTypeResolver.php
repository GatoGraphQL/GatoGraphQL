<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

class RootReplyCommentInputObjectTypeResolver extends AbstractAddCommentToCustomPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootReplyCommentInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to add a comment to a custom post', 'media-mutations');
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
