<?php

declare(strict_types=1);

namespace PoPSchema\Categories\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\Categories\Routing\RouteNatures;
use PoPSchema\Taxonomies\Facades\TaxonomyTypeAPIFacade;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addAction(
            'augmentVarsProperties',
            [$this, 'augmentVarsProperties'],
            10,
            1
        );
    }

    public function augmentVarsProperties(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;
        $nature = $vars['nature'];
        $vars['routing-state']['is-category'] = $nature == RouteNatures::CATEGORY;

        // Save the name of the taxonomy as an attribute,
        // needed to match the RouteModuleProcessor vars conditions
        if ($nature == RouteNatures::CATEGORY) {
            $taxonomyTypeAPI = TaxonomyTypeAPIFacade::getInstance();
            $termObjectID = $vars['routing-state']['queried-object-id'];
            $vars['routing-state']['taxonomy-name'] = $taxonomyTypeAPI->getTermTaxonomyName($termObjectID);
        }
    }
}
