<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserStateMutations\ObjectTypeResolverPickers\AbstractUserIsNotLoggedInErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\AbstractPageUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\AbstractRootCreatePageMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\AbstractRootUpdatePageMutationErrorPayloadUnionTypeResolver;

class UserIsNotLoggedInMutationErrorPayloadObjectTypeResolverPicker extends AbstractUserIsNotLoggedInErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            // AbstractPageMutationErrorPayloadUnionTypeResolver::class,
            AbstractPageUpdateMutationErrorPayloadUnionTypeResolver::class,
            AbstractRootCreatePageMutationErrorPayloadUnionTypeResolver::class,
            AbstractRootUpdatePageMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
