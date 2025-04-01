<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType;

class RootDeleteCommentMetaInputObjectTypeResolver extends AbstractDeleteCommentMetaInputObjectTypeResolver implements DeleteCommentMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootDeleteCommentMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
