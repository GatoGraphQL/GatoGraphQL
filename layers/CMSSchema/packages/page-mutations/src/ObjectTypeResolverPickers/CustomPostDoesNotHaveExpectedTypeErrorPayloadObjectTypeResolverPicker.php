<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers\AbstractCustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\PageUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\RootCreatePageMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolverPicker extends AbstractCustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            // For the custom post parent
            RootCreatePageMutationErrorPayloadUnionTypeResolver::class,
            PageUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
