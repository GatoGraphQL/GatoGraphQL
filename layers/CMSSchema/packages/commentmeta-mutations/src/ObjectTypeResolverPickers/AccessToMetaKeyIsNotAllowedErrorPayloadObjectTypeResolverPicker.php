<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootCreateCommentMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootUpdateCommentMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentUpdateMutationErrorPayloadUnionTypeResolver;

class AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker extends AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCommentMetaMutationErrorPayloadUnionTypeResolver::class,
            RootCreateCommentMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateCommentMutationErrorPayloadUnionTypeResolver::class,
            CommentUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
