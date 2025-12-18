<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers\AbstractMenuDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\AbstractCreateMenuMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\AbstractUpdateMenuMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class MenuDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractMenuDoesNotExistErrorPayloadObjectTypeResolverPicker
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
