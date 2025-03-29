<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\Hooks;

use PoPCMSSchema\MetaMutations\Hooks\AbstractMetaMutationResolverHookSet;
use PoPCMSSchema\MetaMutations\TypeAPIs\MetaTypeMutationAPIInterface;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers\MutateTaxonomyTermMetaMutationResolverTrait;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;

abstract class AbstractTaxonomyMetaMutationResolverHookSet extends AbstractMetaMutationResolverHookSet
{
    use MutateTaxonomyTermMetaMutationResolverTrait;

    private ?TaxonomyMetaTypeMutationAPIInterface $taxonomyMetaTypeMutationAPI = null;
    private ?TaxonomyMetaTypeAPIInterface $taxonomyMetaTypeAPI = null;

    final protected function getTaxonomyMetaTypeMutationAPI(): TaxonomyMetaTypeMutationAPIInterface
    {
        if ($this->taxonomyMetaTypeMutationAPI === null) {
            /** @var TaxonomyMetaTypeMutationAPIInterface */
            $taxonomyMetaTypeMutationAPI = $this->instanceManager->getInstance(TaxonomyMetaTypeMutationAPIInterface::class);
            $this->taxonomyMetaTypeMutationAPI = $taxonomyMetaTypeMutationAPI;
        }
        return $this->taxonomyMetaTypeMutationAPI;
    }
    final protected function getTaxonomyMetaTypeAPI(): TaxonomyMetaTypeAPIInterface
    {
        if ($this->taxonomyMetaTypeAPI === null) {
            /** @var TaxonomyMetaTypeAPIInterface */
            $taxonomyMetaTypeAPI = $this->instanceManager->getInstance(TaxonomyMetaTypeAPIInterface::class);
            $this->taxonomyMetaTypeAPI = $taxonomyMetaTypeAPI;
        }
        return $this->taxonomyMetaTypeAPI;
    }

    protected function getMetaTypeMutationAPI(): MetaTypeMutationAPIInterface
    {
        return $this->getTaxonomyMetaTypeMutationAPI();
    }

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getTaxonomyMetaTypeAPI();
    }
}
