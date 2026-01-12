<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\AbstractCreateMenuMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\AbstractUpdateMenuMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCreateMenuMutationErrorPayloadUnionTypeResolver::class,
            AbstractUpdateMenuMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
