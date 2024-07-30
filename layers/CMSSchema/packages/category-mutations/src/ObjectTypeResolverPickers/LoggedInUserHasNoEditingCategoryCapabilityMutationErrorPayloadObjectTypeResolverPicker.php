<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractCategoryMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoEditingCategoryCapabilityMutationErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCategoryMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
