<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\AbstractSetTagsOnCustomPostMutationResolver;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;

class SetTagsOnCustomPostMutationResolver extends AbstractSetTagsOnCustomPostMutationResolver
{
    private ?QueryableTagTypeAPIInterface $queryableTagTypeAPI = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

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

    protected function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getQueryableTagTypeAPI();
    }

    protected function getTagTaxonomyName(): string
    {
        return '';
    }
}
