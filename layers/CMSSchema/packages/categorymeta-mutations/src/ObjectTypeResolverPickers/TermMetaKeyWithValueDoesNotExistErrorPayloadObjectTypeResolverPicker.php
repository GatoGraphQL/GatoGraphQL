<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
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
            AbstractRootUpdateCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
