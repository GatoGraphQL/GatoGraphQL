<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType;

class CommentUpdateMetaInputObjectTypeResolver extends AbstractUpdateCommentMetaInputObjectTypeResolver implements UpdateCommentMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CommentUpdateMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
