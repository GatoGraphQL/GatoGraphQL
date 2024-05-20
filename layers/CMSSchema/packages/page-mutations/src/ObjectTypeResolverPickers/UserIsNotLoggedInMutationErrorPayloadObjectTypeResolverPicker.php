<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\AbstractPageMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserStateMutations\ObjectTypeResolverPickers\AbstractUserIsNotLoggedInErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UserIsNotLoggedInMutationErrorPayloadObjectTypeResolverPicker extends AbstractUserIsNotLoggedInErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractPageMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
