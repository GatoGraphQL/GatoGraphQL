<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType;

class CommentAddMetaInputObjectTypeResolver extends AbstractAddCommentMetaInputObjectTypeResolver implements AddCommentMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CommentAddMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
