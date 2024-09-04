<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\TypeResolvers\InputObjectType;

use PoPCMSSchema\PostTags\TypeResolvers\EnumType\PostTagTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\AbstractFilterCustomPostsByTagsInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class PostsFilterCustomPostsByTagsInputObjectTypeResolver extends AbstractFilterCustomPostsByTagsInputObjectTypeResolver
{
    private ?PostTagTaxonomyEnumStringScalarTypeResolver $postTagTaxonomyEnumStringScalarTypeResolver = null;

    final public function setPostTagTaxonomyEnumStringScalarTypeResolver(PostTagTaxonomyEnumStringScalarTypeResolver $postTagTaxonomyEnumStringScalarTypeResolver): void
    {
        $this->postTagTaxonomyEnumStringScalarTypeResolver = $postTagTaxonomyEnumStringScalarTypeResolver;
    }
    final protected function getPostTagTaxonomyEnumStringScalarTypeResolver(): PostTagTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->postTagTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var PostTagTaxonomyEnumStringScalarTypeResolver */
            $postTagTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(PostTagTaxonomyEnumStringScalarTypeResolver::class);
            $this->postTagTaxonomyEnumStringScalarTypeResolver = $postTagTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->postTagTaxonomyEnumStringScalarTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'FilterPostsByTagsInput';
    }

    protected function getTagTaxonomyFilterInput(): InputTypeResolverInterface
    {
        return $this->getPostTagTaxonomyEnumStringScalarTypeResolver();
    }

    protected function getTagTaxonomyFilterDefaultValue(): mixed
    {
        return null;
    }
}
