<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\Hooks;

use PoPCMSSchema\MetaMutations\Hooks\AbstractMetaMutationResolverHookSet;
use PoPCMSSchema\MetaMutations\TypeAPIs\MetaTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;

abstract class AbstractTaxonomyMetaMutationResolverHookSet extends AbstractMetaMutationResolverHookSet
{
    private ?TaxonomyMetaTypeMutationAPIInterface $taxonomyTypeMutationAPI = null;

    final protected function getTaxonomyMetaTypeMutationAPI(): TaxonomyMetaTypeMutationAPIInterface
    {
        if ($this->taxonomyTypeMutationAPI === null) {
            /** @var TaxonomyMetaTypeMutationAPIInterface */
            $taxonomyTypeMutationAPI = $this->instanceManager->getInstance(TaxonomyMetaTypeMutationAPIInterface::class);
            $this->taxonomyTypeMutationAPI = $taxonomyTypeMutationAPI;
        }
        return $this->taxonomyTypeMutationAPI;
    }

    protected function getMetaTypeMutationAPI(): MetaTypeMutationAPIInterface
    {
        return $this->getTaxonomyMetaTypeMutationAPI();
    }
}
