<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoEditingCommentCapabilityErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoEditingCommentCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingCommentCapabilityErrorPayloadObjectTypeResolverPicker
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
