<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType;

class CommentDeleteMetaInputObjectTypeResolver extends AbstractDeleteCommentMetaInputObjectTypeResolver implements DeleteCommentMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CommentDeleteMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
