<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostUserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostUserMutations\TypeResolvers\UnionType\CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostUserMutations\TypeResolvers\UnionType\RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UserDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends AbstractUserDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver::class,
            CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
