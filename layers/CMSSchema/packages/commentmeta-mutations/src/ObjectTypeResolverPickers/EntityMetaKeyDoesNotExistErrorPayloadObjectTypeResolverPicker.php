<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractRootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractEntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class EntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractEntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractCommentDeleteMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
