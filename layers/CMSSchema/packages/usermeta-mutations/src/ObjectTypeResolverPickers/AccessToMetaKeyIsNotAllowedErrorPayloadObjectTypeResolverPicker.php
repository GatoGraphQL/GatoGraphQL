<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootCreateGenericUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootUpdateGenericUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\GenericUserUpdateMutationErrorPayloadUnionTypeResolver;

class AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker extends AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractUserMetaMutationErrorPayloadUnionTypeResolver::class,
            RootCreateGenericUserMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericUserMutationErrorPayloadUnionTypeResolver::class,
            GenericUserUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
