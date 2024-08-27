<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractTagUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractRootCreateTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractRootUpdateTagTermMutationErrorPayloadUnionTypeResolver;

class LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractTagUpdateMutationErrorPayloadUnionTypeResolver::class,
            AbstractRootCreateTagTermMutationErrorPayloadUnionTypeResolver::class,
            AbstractRootUpdateTagTermMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
