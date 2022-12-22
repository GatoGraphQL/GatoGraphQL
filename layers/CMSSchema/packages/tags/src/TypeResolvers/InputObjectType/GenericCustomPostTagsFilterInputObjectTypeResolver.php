<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

use PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class GenericCustomPostTagsFilterInputObjectTypeResolver extends AbstractTagsFilterInputObjectTypeResolver
{
    private ?TagTaxonomyEnumTypeResolver $tagTaxonomyEnumTypeResolver = null;

    final public function setTagTaxonomyEnumTypeResolver(TagTaxonomyEnumTypeResolver $tagTaxonomyEnumTypeResolver): void
    {
        $this->tagTaxonomyEnumTypeResolver = $tagTaxonomyEnumTypeResolver;
    }
    final protected function getTagTaxonomyEnumTypeResolver(): TagTaxonomyEnumTypeResolver
    {
        /** @var TagTaxonomyEnumTypeResolver */
        return $this->tagTaxonomyEnumTypeResolver ??= $this->instanceManager->getInstance(TagTaxonomyEnumTypeResolver::class);
    }
    
    public function getTypeName(): string
    {
        return 'GenericCustomPostTagsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter tags from a generic custom post', 'tags');
    }

    protected function getTaxonomyInputFieldTypeResolver(): ?InputTypeResolverInterface
    {
        return $this->getTagTaxonomyEnumTypeResolver();
    }
}
