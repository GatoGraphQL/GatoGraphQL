<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType;

class CommentUpdateInputObjectTypeResolver extends AbstractUpdateCommentInputObjectTypeResolver
{
    protected function addIDInputField(): bool
    {
        return false;
    }

    public function getTypeName(): string
    {
        return 'CommentUpdateInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a comment (nested mutations)', 'gatographql');
    }
}
