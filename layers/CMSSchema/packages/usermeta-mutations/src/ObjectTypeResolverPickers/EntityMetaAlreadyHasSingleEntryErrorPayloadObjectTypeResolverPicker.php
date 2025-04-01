<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\GenericUserUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootCreateGenericUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootUpdateGenericUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractRootAddUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractEntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker extends AbstractEntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootAddUserMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractUserAddMetaMutationErrorPayloadUnionTypeResolver::class,
            RootCreateGenericUserMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericUserMutationErrorPayloadUnionTypeResolver::class,
            GenericUserUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
