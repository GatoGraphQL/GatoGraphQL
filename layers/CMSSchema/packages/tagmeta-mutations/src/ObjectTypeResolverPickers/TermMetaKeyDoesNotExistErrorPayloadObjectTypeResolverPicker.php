<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractTagDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractRootDeleteTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractTermMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TermMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractTermMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootDeleteTagTermMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractTagDeleteMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
