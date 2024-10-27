<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class TagTermUpdateInputObjectTypeResolver extends AbstractCreateOrUpdateTagTermInputObjectTypeResolver implements UpdateTagTermInputObjectTypeResolverInterface
{
    use TagTermUpdateInputObjectTypeResolverTrait;

    private ?TagTaxonomyEnumStringScalarTypeResolver $tagTaxonomyEnumStringScalarTypeResolver = null;

    final protected function getTagTaxonomyEnumStringScalarTypeResolver(): TagTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->tagTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var TagTaxonomyEnumStringScalarTypeResolver */
            $tagTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(TagTaxonomyEnumStringScalarTypeResolver::class);
            $this->tagTaxonomyEnumStringScalarTypeResolver = $tagTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->tagTaxonomyEnumStringScalarTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'TagUpdateInput';
    }

    protected function getTaxonomyInputObjectTypeResolver(): InputTypeResolverInterface
    {
        return $this->getTagTaxonomyEnumStringScalarTypeResolver();
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }

    protected function getTaxonomyInputFieldDefaultValue(): mixed
    {
        return null;
    }
}
