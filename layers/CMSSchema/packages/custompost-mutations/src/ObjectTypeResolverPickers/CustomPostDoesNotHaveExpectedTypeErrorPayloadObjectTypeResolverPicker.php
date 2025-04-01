<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolverPicker extends AbstractCustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
