<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\AbstractUpdateUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\AbstractDeleteUserMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UserDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractUserDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractUpdateUserMutationErrorPayloadUnionTypeResolver::class,
            AbstractDeleteUserMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
