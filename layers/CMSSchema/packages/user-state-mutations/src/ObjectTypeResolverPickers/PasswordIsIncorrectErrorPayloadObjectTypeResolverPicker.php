<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\RootLoginUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserStateMutations\ObjectTypeResolverPickers\AbstractPasswordIsIncorrectErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PasswordIsIncorrectErrorPayloadObjectTypeResolverPicker extends AbstractPasswordIsIncorrectErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            RootLoginUserMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
