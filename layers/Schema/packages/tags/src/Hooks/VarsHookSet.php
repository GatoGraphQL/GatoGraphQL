<?php

declare(strict_types=1);

namespace PoPSchema\Tags\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\Tags\Routing\RouteNatures;

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
        $vars['routing-state']['is-tag'] = $nature == RouteNatures::TAG;

        // Save the name of the taxonomy as an attribute,
        // needed to match the RouteModuleProcessor vars conditions
        if ($nature == RouteNatures::TAG) {
            \PoPSchema\Taxonomies\VarsHelpers::addQueriedObjectTaxonomyNameToVars($vars);
        }
    }
}
