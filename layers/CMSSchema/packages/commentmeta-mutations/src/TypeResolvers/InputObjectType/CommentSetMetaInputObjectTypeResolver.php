<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType;

class CommentSetMetaInputObjectTypeResolver extends AbstractSetCommentMetaInputObjectTypeResolver implements SetCommentMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CommentSetMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
