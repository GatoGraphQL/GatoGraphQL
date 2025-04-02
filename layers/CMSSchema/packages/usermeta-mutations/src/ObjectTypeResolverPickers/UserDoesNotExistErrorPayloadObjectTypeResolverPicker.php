<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\ObjectTypeResolverPickers\AbstractUserDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractRootUserMetaMutationErrorPayloadUnionTypeResolver;

class UserDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractUserDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootUserMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
