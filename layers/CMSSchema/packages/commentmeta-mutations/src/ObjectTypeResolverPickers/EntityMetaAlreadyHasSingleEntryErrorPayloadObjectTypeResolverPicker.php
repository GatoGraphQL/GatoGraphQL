<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\GenericCommentUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootCreateGenericCommentMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootUpdateGenericCommentMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractRootAddCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractEntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker extends AbstractEntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootAddCommentMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractCommentAddMetaMutationErrorPayloadUnionTypeResolver::class,
            RootCreateGenericCommentMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCommentMutationErrorPayloadUnionTypeResolver::class,
            GenericCommentUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
