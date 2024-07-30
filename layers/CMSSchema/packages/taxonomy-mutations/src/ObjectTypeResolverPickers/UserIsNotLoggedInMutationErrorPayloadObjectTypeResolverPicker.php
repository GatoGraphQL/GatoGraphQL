<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType\AbstractTaxonomyMutationErrorPayloadUnionTypeResolver;
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
            AbstractTaxonomyMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
