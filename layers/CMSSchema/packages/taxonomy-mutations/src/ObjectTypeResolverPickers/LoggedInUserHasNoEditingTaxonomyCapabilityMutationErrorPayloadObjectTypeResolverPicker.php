<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType\AbstractTaxonomyMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoEditingTaxonomyCapabilityMutationErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolverPicker
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
