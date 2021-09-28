<?php

declare(strict_types=1);

namespace PoPSchema\Tags\Hooks;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Tags\Routing\RouteNatures;
use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

class VarsHookSet extends AbstractHookSet
{
    protected TaxonomyTypeAPIInterface $taxonomyTypeAPI;

    #[Required]
    public function autowireVarsHookSet(
        TaxonomyTypeAPIInterface $taxonomyTypeAPI,
    ) {
        $this->taxonomyTypeAPI = $taxonomyTypeAPI;
    }

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
            $termObjectID = $vars['routing-state']['queried-object-id'];
            $vars['routing-state']['taxonomy-name'] = $this->taxonomyTypeAPI->getTermTaxonomyName($termObjectID);
        }
    }
}
