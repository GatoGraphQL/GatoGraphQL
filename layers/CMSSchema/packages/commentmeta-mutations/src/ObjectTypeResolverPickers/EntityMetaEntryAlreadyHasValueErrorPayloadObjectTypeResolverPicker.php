<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractEntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolverPicker extends AbstractEntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractCommentUpdateMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
