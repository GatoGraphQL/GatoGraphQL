<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType;

class RootAddCommentMetaInputObjectTypeResolver extends AbstractAddCommentMetaInputObjectTypeResolver implements AddCommentMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootAddCommentMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
