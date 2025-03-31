<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractTagUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractEntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractEntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker
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
