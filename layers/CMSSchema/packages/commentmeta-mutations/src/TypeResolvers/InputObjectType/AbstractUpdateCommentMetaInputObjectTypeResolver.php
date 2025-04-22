<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\TypeResolvers\InputObjectType\AbstractUpdateEntityMetaInputObjectTypeResolver;

abstract class AbstractUpdateCommentMetaInputObjectTypeResolver extends AbstractUpdateEntityMetaInputObjectTypeResolver implements UpdateCommentMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a comment\'s meta', 'commentmeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the comment', 'commentmeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
