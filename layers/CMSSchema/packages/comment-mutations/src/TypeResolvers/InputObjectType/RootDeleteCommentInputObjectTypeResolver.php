<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType;

class RootDeleteCommentInputObjectTypeResolver extends AbstractDeleteCommentInputObjectTypeResolver
{
    protected function addIDInputField(): bool
    {
        return true;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteCommentInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a comment', 'gatographql');
    }
}
