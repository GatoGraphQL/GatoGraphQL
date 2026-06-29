<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\TypeResolvers\InputObjectType\AbstractAddEntityMetaInputObjectTypeResolver;

abstract class AbstractAddCommentMetaInputObjectTypeResolver extends AbstractAddEntityMetaInputObjectTypeResolver implements AddCommentMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to add meta to a comment', 'gatographql');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the comment', 'gatographql'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
