<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractTagUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractEntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolverPicker extends AbstractEntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootUpdateTagTermMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractTagUpdateMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
