<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

use PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class CustomPostsFilterCustomPostsByTagsInputObjectTypeResolver extends AbstractFilterCustomPostsByTagsInputObjectTypeResolver
{
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
        return 'FilterCustomPostsByTagsInput';
    }

    protected function getTagTaxonomyFilterInput(): InputTypeResolverInterface
    {
        return $this->getTagTaxonomyEnumStringScalarTypeResolver();
    }

    protected function getTagTaxonomyFilterDefaultValue(): mixed
    {
        return null;
    }
}
