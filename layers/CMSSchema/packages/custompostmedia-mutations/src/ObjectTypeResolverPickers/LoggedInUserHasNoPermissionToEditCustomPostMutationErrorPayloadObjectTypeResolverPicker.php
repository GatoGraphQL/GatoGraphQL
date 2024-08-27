<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\AbstractCustomPostMediaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostMediaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
