<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType;

class RootUpdateCommentMetaInputObjectTypeResolver extends AbstractUpdateCommentMetaInputObjectTypeResolver implements UpdateCommentMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateCommentMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
