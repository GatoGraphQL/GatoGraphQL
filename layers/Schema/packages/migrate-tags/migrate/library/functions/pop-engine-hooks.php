<?php
namespace PoPSchema\Tags;
use PoPSchema\Tags\Routing\RouteNatures;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Taxonomies\Facades\TaxonomyTypeAPIFacade;

class Engine_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'augmentVarsProperties',
            [$this, 'augmentVarsProperties'],
            10,
            1
        );
    }

    /**
     * @param array<array> $vars_in_array
     */
    public function augmentVarsProperties(array $vars_in_array): void
    {
        // Set additional properties based on the nature: the global $post, $author, or $queried_object
        $vars = &$vars_in_array[0];
        $nature = $vars['nature'];
        $vars['routing-state']['is-tag'] = $nature == RouteNatures::TAG;

        // Save the name of the taxonomy as an attribute,
        // needed to match the RouteModuleProcessor vars conditions
        if ($nature == RouteNatures::TAG) {
            \PoPSchema\Taxonomies\VarsHelpers::addQueriedObjectTaxonomyNameToVars($vars);
        }
    }
}

/**
 * Initialization
 */
new Engine_Hooks();
