<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\ObjectTypeResolverPickers;

use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TagDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends AbstractTagDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            /**
             * Commented because the input is an Enum type, hence
             * the taxonomy will always exist
             */
            // AbstractRootCreateTagMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
