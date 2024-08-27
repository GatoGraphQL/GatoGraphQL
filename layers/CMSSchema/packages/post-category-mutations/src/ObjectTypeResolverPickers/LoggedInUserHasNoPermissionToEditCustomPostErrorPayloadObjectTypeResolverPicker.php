<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\AbstractPostCategoriesMutationErrorPayloadUnionTypeResolver;
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
            AbstractPostCategoriesMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
