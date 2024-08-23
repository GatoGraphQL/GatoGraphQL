<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\Hooks;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\GenericCustomPostCategoryTypeMutationAPIInterface;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    private ?GenericCustomPostCategoryTypeMutationAPIInterface $genericCustomPostCategoryTypeMutationAPI = null;
    private ?QueryableCategoryTypeAPIInterface $queryableCategoryTypeAPI = null;

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

    protected function getCustomPostType(): string
    {
        return '';
    }

    protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface
    {
        return $this->getGenericCustomPostCategoryTypeMutationAPI();
    }

    protected function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getQueryableCategoryTypeAPI();
    }
}
