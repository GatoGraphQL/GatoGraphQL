<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractEntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolverPicker extends AbstractEntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootUpdateUserMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractUserUpdateMetaMutationErrorPayloadUnionTypeResolver::class,
            // @todo Re-enable when adding User Mutations
            // RootCreateUserMutationErrorPayloadUnionTypeResolver::class,
            // RootUpdateUserMutationErrorPayloadUnionTypeResolver::class,
            // UserUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
