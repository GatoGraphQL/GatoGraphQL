<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPCMSSchema\Categories\Routing\RequestNature;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final public function setTaxonomyTypeAPI(TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI): void
    {
        $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
    }
    final protected function getTaxonomyTypeAPI(): TaxonomyTermTypeAPIInterface
    {
        /** @var TaxonomyTermTypeAPIInterface */
        return $this->taxonomyTermTypeAPI ??= $this->instanceManager->getInstance(TaxonomyTermTypeAPIInterface::class);
    }

    /**
     * @param array<string,mixed> $state
     */
    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing']['is-category'] = $nature === RequestNature::CATEGORY;

        // Save the name of the taxonomy as an attribute,
        // needed to match the ComponentRoutingProcessor vars conditions
        if ($nature === RequestNature::CATEGORY) {
            $termObjectID = $state['routing']['queried-object-id'];
            $state['routing']['taxonomy-name'] = $this->getTaxonomyTypeAPI()->getTermTaxonomyName($termObjectID);
        }
    }
}
