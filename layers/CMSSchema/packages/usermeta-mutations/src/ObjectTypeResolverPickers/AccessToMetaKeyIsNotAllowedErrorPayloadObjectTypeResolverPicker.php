<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootCreateUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootUpdateUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\UserUpdateMutationErrorPayloadUnionTypeResolver;

class AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker extends AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractUserMetaMutationErrorPayloadUnionTypeResolver::class,
            RootCreateUserMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateUserMutationErrorPayloadUnionTypeResolver::class,
            UserUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
