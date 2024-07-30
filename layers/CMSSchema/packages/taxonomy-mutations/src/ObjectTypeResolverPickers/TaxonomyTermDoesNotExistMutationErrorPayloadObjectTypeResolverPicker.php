<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType\AbstractRootUpdateTaxonomyMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TaxonomyTermDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends AbstractTaxonomyTermDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootUpdateTaxonomyMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
