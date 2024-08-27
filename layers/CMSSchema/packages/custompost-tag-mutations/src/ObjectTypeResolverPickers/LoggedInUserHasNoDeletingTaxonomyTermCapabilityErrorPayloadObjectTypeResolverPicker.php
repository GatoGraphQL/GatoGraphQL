<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\GenericTagDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver::class,
            GenericTagDeleteMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
