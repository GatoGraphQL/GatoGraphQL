<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\TypeResolvers\InputObjectType;

use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\AbstractFixedTaxonomyFilterCustomPostsByTagsInputObjectTypeResolver;

class PostsFilterCustomPostsByTagsInputObjectTypeResolver extends AbstractFixedTaxonomyFilterCustomPostsByTagsInputObjectTypeResolver
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
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
        return 'FilterPostsByTagsInput';
    }

    protected function getTagTaxonomyName(): string
    {
        return $this->getPostTagTypeAPI()->getPostTagTaxonomyName();
    }
}
