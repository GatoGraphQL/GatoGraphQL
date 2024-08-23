<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\AbstractSetTagsOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\GenericCustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;

class SetTagsOnCustomPostMutationResolver extends AbstractSetTagsOnCustomPostMutationResolver
{
    private ?GenericCustomPostTagTypeMutationAPIInterface $genericCustomPostTagTypeMutationAPI = null;
    private ?QueryableTagTypeAPIInterface $queryableTagTypeAPI = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final public function setGenericCustomPostTagTypeMutationAPI(GenericCustomPostTagTypeMutationAPIInterface $genericCustomPostTagTypeMutationAPI): void
    {
        $this->genericCustomPostTagTypeMutationAPI = $genericCustomPostTagTypeMutationAPI;
    }
    final protected function getGenericCustomPostTagTypeMutationAPI(): GenericCustomPostTagTypeMutationAPIInterface
    {
        if ($this->genericCustomPostTagTypeMutationAPI === null) {
            /** @var GenericCustomPostTagTypeMutationAPIInterface */
            $genericCustomPostTagTypeMutationAPI = $this->instanceManager->getInstance(GenericCustomPostTagTypeMutationAPIInterface::class);
            $this->genericCustomPostTagTypeMutationAPI = $genericCustomPostTagTypeMutationAPI;
        }
        return $this->genericCustomPostTagTypeMutationAPI;
    }
    final public function setQueryableTagTypeAPI(QueryableTagTypeAPIInterface $queryableTagTypeAPI): void
    {
        $this->queryableTagTypeAPI = $queryableTagTypeAPI;
    }
    final protected function getQueryableTagTypeAPI(): QueryableTagTypeAPIInterface
    {
        if ($this->queryableTagTypeAPI === null) {
            /** @var QueryableTagTypeAPIInterface */
            $queryableTagTypeAPI = $this->instanceManager->getInstance(QueryableTagTypeAPIInterface::class);
            $this->queryableTagTypeAPI = $queryableTagTypeAPI;
        }
        return $this->queryableTagTypeAPI;
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
        return $this->getGenericCustomPostTagTypeMutationAPI();
    }

    protected function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getQueryableTagTypeAPI();
    }

    protected function getTagTaxonomyName(): string
    {
        return '';
    }
}
