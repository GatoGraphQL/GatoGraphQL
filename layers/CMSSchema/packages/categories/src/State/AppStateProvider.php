<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPCMSSchema\Categories\Routing\RequestNature;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final protected function getTaxonomyTermTypeAPI(): TaxonomyTermTypeAPIInterface
    {
        if ($this->taxonomyTermTypeAPI === null) {
            /** @var TaxonomyTermTypeAPIInterface */
            $taxonomyTermTypeAPI = $this->instanceManager->getInstance(TaxonomyTermTypeAPIInterface::class);
            $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
        }
        return $this->taxonomyTermTypeAPI;
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
            $termObject = $state['routing']['queried-object'];
            $state['routing']['taxonomy-name'] = $this->getTaxonomyTermTypeAPI()->getTermTaxonomyName($termObject);
        }
    }
}
