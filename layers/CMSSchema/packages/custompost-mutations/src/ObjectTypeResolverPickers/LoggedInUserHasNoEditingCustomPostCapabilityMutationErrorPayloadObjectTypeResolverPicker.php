<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\CustomPostUpdateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoEditingCustomPostCapabilityMutationErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostUpdateMutationErrorPayloadUnionTypeResolver::class,
            CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
