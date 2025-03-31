<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractTagAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractRootAddTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker extends AbstractTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootAddTagTermMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractTagAddMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
