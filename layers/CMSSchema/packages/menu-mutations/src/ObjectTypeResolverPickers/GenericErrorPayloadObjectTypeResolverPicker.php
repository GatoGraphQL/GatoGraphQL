<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\AbstractCreateMenuMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\AbstractUpdateMenuMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractGenericErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericErrorPayloadObjectTypeResolverPicker extends AbstractGenericErrorPayloadObjectTypeResolverPicker
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
