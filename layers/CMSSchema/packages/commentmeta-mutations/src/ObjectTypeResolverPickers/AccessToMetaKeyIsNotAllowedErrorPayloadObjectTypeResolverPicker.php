<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootCreateGenericCommentMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootUpdateGenericCommentMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\GenericCommentUpdateMutationErrorPayloadUnionTypeResolver;

class AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker extends AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCommentMetaMutationErrorPayloadUnionTypeResolver::class,
            RootCreateGenericCommentMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCommentMutationErrorPayloadUnionTypeResolver::class,
            GenericCommentUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
