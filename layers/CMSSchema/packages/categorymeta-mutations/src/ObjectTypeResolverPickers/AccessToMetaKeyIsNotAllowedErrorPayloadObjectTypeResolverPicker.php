<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategoryMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver;

class AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker extends AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCategoryMetaMutationErrorPayloadUnionTypeResolver::class,
            RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
