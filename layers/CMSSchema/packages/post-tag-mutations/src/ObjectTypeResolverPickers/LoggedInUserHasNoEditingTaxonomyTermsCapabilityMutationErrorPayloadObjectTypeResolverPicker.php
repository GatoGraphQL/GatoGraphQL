<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractTagUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractRootCreateTagMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractRootUpdateTagMutationErrorPayloadUnionTypeResolver;

class LoggedInUserHasNoEditingTaxonomyTermsCapabilityMutationErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractTagUpdateMutationErrorPayloadUnionTypeResolver::class,
            AbstractRootCreateTagMutationErrorPayloadUnionTypeResolver::class,
            AbstractRootUpdateTagMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
