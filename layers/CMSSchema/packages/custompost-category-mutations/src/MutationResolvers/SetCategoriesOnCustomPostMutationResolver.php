<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\AbstractSetCategoriesOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\GenericCustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;

class SetCategoriesOnCustomPostMutationResolver extends AbstractSetCategoriesOnCustomPostMutationResolver
{
    private ?GenericCustomPostCategoryTypeMutationAPIInterface $genericCustomPostCategoryTypeMutationAPI = null;
    private ?QueryableCategoryTypeAPIInterface $queryableCategoryTypeAPI = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final public function setGenericCustomPostCategoryTypeMutationAPI(GenericCustomPostCategoryTypeMutationAPIInterface $genericCustomPostCategoryTypeMutationAPI): void
    {
        $this->genericCustomPostCategoryTypeMutationAPI = $genericCustomPostCategoryTypeMutationAPI;
    }
    final protected function getGenericCustomPostCategoryTypeMutationAPI(): GenericCustomPostCategoryTypeMutationAPIInterface
    {
        if ($this->genericCustomPostCategoryTypeMutationAPI === null) {
            /** @var GenericCustomPostCategoryTypeMutationAPIInterface */
            $genericCustomPostCategoryTypeMutationAPI = $this->instanceManager->getInstance(GenericCustomPostCategoryTypeMutationAPIInterface::class);
            $this->genericCustomPostCategoryTypeMutationAPI = $genericCustomPostCategoryTypeMutationAPI;
        }
        return $this->genericCustomPostCategoryTypeMutationAPI;
    }
    final public function setQueryableCategoryTypeAPI(QueryableCategoryTypeAPIInterface $queryableCategoryTypeAPI): void
    {
        $this->queryableCategoryTypeAPI = $queryableCategoryTypeAPI;
    }
    final protected function getQueryableCategoryTypeAPI(): QueryableCategoryTypeAPIInterface
    {
        if ($this->queryableCategoryTypeAPI === null) {
            /** @var QueryableCategoryTypeAPIInterface */
            $queryableCategoryTypeAPI = $this->instanceManager->getInstance(QueryableCategoryTypeAPIInterface::class);
            $this->queryableCategoryTypeAPI = $queryableCategoryTypeAPI;
        }
        return $this->queryableCategoryTypeAPI;
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
        return $this->getGenericCustomPostCategoryTypeMutationAPI();
    }

    protected function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getQueryableCategoryTypeAPI();
    }

    protected function getCategoryTaxonomyName(): string
    {
        return '';
    }
}
