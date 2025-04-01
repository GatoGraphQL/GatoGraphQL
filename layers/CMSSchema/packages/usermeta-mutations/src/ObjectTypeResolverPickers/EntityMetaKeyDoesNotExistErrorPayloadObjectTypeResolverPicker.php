<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractRootDeleteUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractEntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class EntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractEntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootDeleteUserMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractUserDeleteMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
