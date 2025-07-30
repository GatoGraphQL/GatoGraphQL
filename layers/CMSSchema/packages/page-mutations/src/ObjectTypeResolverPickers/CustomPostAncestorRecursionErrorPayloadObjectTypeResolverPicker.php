<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers\AbstractCustomPostAncestorRecursionErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\PageUpdateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CustomPostAncestorRecursionErrorPayloadObjectTypeResolverPicker extends AbstractCustomPostAncestorRecursionErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            // For the custom post parent
            PageUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
