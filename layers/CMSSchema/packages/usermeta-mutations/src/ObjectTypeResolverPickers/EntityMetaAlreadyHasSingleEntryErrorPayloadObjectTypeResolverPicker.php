<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\ObjectTypeResolverPickers;

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
            // @todo Re-enable when adding User Mutations
            // RootCreateUserMutationErrorPayloadUnionTypeResolver::class,
            // RootUpdateUserMutationErrorPayloadUnionTypeResolver::class,
            // UserUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
