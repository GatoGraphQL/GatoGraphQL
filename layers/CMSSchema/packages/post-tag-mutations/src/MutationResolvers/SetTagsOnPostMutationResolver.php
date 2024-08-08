<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\MutationResolvers;

use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\AbstractSetTagsOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;

class SetTagsOnPostMutationResolver extends AbstractSetTagsOnCustomPostMutationResolver
{
    private ?PostTagTypeMutationAPIInterface $postTagTypeMutationAPIInterface = null;
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final public function setPostTagTypeMutationAPI(PostTagTypeMutationAPIInterface $postTagTypeMutationAPIInterface): void
    {
        $this->postTagTypeMutationAPIInterface = $postTagTypeMutationAPIInterface;
    }
    final protected function getPostTagTypeMutationAPI(): PostTagTypeMutationAPIInterface
    {
        if ($this->postTagTypeMutationAPIInterface === null) {
            /** @var PostTagTypeMutationAPIInterface */
            $postTagTypeMutationAPIInterface = $this->instanceManager->getInstance(PostTagTypeMutationAPIInterface::class);
            $this->postTagTypeMutationAPIInterface = $postTagTypeMutationAPIInterface;
        }
        return $this->postTagTypeMutationAPIInterface;
    }
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
    final public function setTaxonomyTermTypeAPI(TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI): void
    {
        $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
    }
    final protected function getTaxonomyTermTypeAPI(): TaxonomyTermTypeAPIInterface
    {
        if ($this->taxonomyTermTypeAPI === null) {
            /** @var TaxonomyTermTypeAPIInterface */
            $taxonomyTermTypeAPI = $this->instanceManager->getInstance(TaxonomyTermTypeAPIInterface::class);
            $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
        }
        return $this->taxonomyTermTypeAPI;
    }

    protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface
    {
        return $this->getPostTagTypeMutationAPI();
    }

    protected function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getPostTagTypeAPI();
    }

    protected function getEntityName(): string
    {
        return $this->__('post', 'post-tag-mutations');
    }

    protected function getTagTaxonomyName(): string
    {
        return $this->getPostTagTypeAPI()->getPostTagTaxonomyName();
    }
}
