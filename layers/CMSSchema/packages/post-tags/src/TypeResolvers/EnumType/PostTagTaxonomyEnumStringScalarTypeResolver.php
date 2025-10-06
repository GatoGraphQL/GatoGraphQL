<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\TypeResolvers\EnumType;

use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\EnumType\AbstractTagTaxonomyEnumStringScalarTypeResolver;

class PostTagTaxonomyEnumStringScalarTypeResolver extends AbstractTagTaxonomyEnumStringScalarTypeResolver
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        if ($this->postTagTypeAPI === null) {
            /** @var PostTagTypeAPIInterface */
            $postTagTypeAPI = $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
            $this->postTagTypeAPI = $postTagTypeAPI;
        }
        return $this->postTagTypeAPI;
    }

    public function getTypeName(): string
    {
        return 'PostTagTaxonomyEnumString';
    }

    public function getEnumStringTypeDescription(): ?string
    {
        return $this->__('Post tag taxonomies (available for querying via the API)', 'tags');
    }

    protected function getRegisteredCustomPostTagTaxonomyNames(): ?array
    {
        return $this->getPostTagTypeAPI()->getRegisteredPostTagTaxonomyNames();
    }
}
