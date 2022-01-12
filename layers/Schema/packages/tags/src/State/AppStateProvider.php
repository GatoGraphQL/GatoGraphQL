<?php

declare(strict_types=1);

namespace PoPSchema\Tags\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPSchema\Tags\Routing\RouteNatures;
use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?TaxonomyTypeAPIInterface $taxonomyTypeAPI = null;

    final public function setTaxonomyTypeAPI(TaxonomyTypeAPIInterface $taxonomyTypeAPI): void
    {
        $this->taxonomyTypeAPI = $taxonomyTypeAPI;
    }
    final protected function getTaxonomyTypeAPI(): TaxonomyTypeAPIInterface
    {
        return $this->taxonomyTypeAPI ??= $this->instanceManager->getInstance(TaxonomyTypeAPIInterface::class);
    }

    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing']['is-tag'] = $nature === RouteNatures::TAG;

        // Save the name of the taxonomy as an attribute,
        // needed to match the RouteModuleProcessor vars conditions
        if ($nature === RouteNatures::TAG) {
            $termObjectID = $state['routing']['queried-object-id'];
            $state['routing']['taxonomy-name'] = $this->getTaxonomyTypeAPI()->getTermTaxonomyName($termObjectID);
        }
    }
}
