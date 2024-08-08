<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\AbstractSetCategoriesOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;

class SetCategoriesOnPostMutationResolver extends AbstractSetCategoriesOnCustomPostMutationResolver
{
    private ?PostCategoryTypeMutationAPIInterface $postCategoryTypeMutationAPI = null;
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final public function setPostCategoryTypeMutationAPI(PostCategoryTypeMutationAPIInterface $postCategoryTypeMutationAPI): void
    {
        $this->postCategoryTypeMutationAPI = $postCategoryTypeMutationAPI;
    }
    final protected function getPostCategoryTypeMutationAPI(): PostCategoryTypeMutationAPIInterface
    {
        if ($this->postCategoryTypeMutationAPI === null) {
            /** @var PostCategoryTypeMutationAPIInterface */
            $postCategoryTypeMutationAPI = $this->instanceManager->getInstance(PostCategoryTypeMutationAPIInterface::class);
            $this->postCategoryTypeMutationAPI = $postCategoryTypeMutationAPI;
        }
        return $this->postCategoryTypeMutationAPI;
    }
    final public function setPostCategoryTypeAPI(PostCategoryTypeAPIInterface $postCategoryTypeAPI): void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        if ($this->postCategoryTypeAPI === null) {
            /** @var PostCategoryTypeAPIInterface */
            $postCategoryTypeAPI = $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
            $this->postCategoryTypeAPI = $postCategoryTypeAPI;
        }
        return $this->postCategoryTypeAPI;
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

    protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface
    {
        return $this->getPostCategoryTypeMutationAPI();
    }

    protected function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getPostCategoryTypeAPI();
    }

    protected function getEntityName(): string
    {
        return $this->__('post', 'post-category-mutations');
    }

    protected function getCategoryTaxonomyName(): string
    {
        return $this->getPostCategoryTypeAPI()->getPostCategoryTaxonomyName();
    }
}
