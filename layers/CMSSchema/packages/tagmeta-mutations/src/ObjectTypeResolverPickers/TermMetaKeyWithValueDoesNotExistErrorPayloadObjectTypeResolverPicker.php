<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractTagUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractTermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractTermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker
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
