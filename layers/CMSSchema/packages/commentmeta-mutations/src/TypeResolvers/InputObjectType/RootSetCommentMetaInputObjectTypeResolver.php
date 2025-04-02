<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType;

class RootSetCommentMetaInputObjectTypeResolver extends AbstractSetCommentMetaInputObjectTypeResolver implements SetCommentMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootSetCommentMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
