<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\GenericTagUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\RootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\RootUpdateGenericTagTermMutationErrorPayloadUnionTypeResolver;
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
            RootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericTagTermMutationErrorPayloadUnionTypeResolver::class,
            GenericTagUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
