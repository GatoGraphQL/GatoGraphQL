<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType;

class RootUpdateCommentInputObjectTypeResolver extends AbstractUpdateCommentInputObjectTypeResolver
{
    protected function addIDInputField(): bool
    {
        return true;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateCommentInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a comment', 'gatographql');
    }
}
