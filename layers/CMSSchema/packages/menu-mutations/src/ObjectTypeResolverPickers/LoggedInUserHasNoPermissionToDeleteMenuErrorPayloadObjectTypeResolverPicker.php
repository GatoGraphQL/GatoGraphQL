<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\AbstractDeleteMenuMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractDeleteMenuMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
