<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\AbstractPageUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\AbstractRootUpdatePageMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoPermissionToEditPageMutationErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootUpdatePageMutationErrorPayloadUnionTypeResolver::class,
            AbstractPageUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
