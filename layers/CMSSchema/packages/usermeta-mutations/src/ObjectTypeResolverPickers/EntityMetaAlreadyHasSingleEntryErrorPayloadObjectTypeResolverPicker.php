<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\UserUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootCreateUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootUpdateUserMutationErrorPayloadUnionTypeResolver;
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
            RootCreateUserMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateUserMutationErrorPayloadUnionTypeResolver::class,
            UserUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
