<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostTagMutations\ObjectTypeResolverPickers\AbstractTagDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\AbstractPostTagsMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TagDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends AbstractTagDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractPostTagsMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
