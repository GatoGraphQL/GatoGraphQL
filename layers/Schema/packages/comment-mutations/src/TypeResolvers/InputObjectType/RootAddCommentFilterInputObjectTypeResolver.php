<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\TypeResolvers\InputObjectType;

class RootAddCommentFilterInputObjectTypeResolver extends AbstractAddCommentToCustomPostFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'AddCommentFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to add a comment', 'comment-mutations');
    }

    protected function addParentInputFields(): bool
    {
        return true;
    }

    protected function isParentCommentMandatory(): bool
    {
        return false;
    }

    protected function addCustomPostInputFields(): bool
    {
        return false;
    }

}
