<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\AbstractCommentMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CommentContentIsMissingMutationErrorPayloadObjectTypeResolverPicker extends AbstractCommentContentIsMissingErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCommentMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
