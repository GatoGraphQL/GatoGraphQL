<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\TypeResolvers\InputObjectType;

class RootAddCommentToCustomPostFilterInputObjectTypeResolver extends AbstractAddCommentToCustomPostFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'AddCommentToCustomPostFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to add a comment to a custom post', 'comment-mutations');
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }

    protected function addParentInputFields(): bool
    {
        return true;
    }

    protected function isParentCommentMandatory(): bool
    {
        return false;
    }
}
