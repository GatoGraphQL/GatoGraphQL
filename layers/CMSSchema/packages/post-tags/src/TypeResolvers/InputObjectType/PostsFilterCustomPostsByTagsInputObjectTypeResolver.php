<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\TypeResolvers\InputObjectType;

use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\PostTags\TypeResolvers\EnumType\PostTagTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\AbstractFilterCustomPostsByTagsInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class PostsFilterCustomPostsByTagsInputObjectTypeResolver extends AbstractFilterCustomPostsByTagsInputObjectTypeResolver
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;
    private ?PostTagTaxonomyEnumStringScalarTypeResolver $postTagTaxonomyEnumStringScalarTypeResolver = null;

    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        if ($this->postTagTypeAPI === null) {
            /** @var PostTagTypeAPIInterface */
            $postTagTypeAPI = $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
            $this->postTagTypeAPI = $postTagTypeAPI;
        }
        return $this->postTagTypeAPI;
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
        $postTagTaxonomyName = $this->getPostTagTypeAPI()->getPostTagTaxonomyName();
        if (!in_array($postTagTaxonomyName, $this->getPostTagTaxonomyEnumStringScalarTypeResolver()->getConsolidatedPossibleValues())) {
            return null;
        }
        return $postTagTaxonomyName;
    }
}
