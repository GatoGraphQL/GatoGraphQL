<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCommentMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
