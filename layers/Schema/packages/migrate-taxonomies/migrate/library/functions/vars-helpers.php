<?php
namespace PoPSchema\Taxonomies;
use PoPSchema\Taxonomies\Facades\TaxonomyTypeAPIFacade;

class VarsHelpers
{
    /**
     * Save the name of the taxonomy as an attribute,
     * needed to match the RouteModuleProcessor vars conditions
     *
     * @param [type] $vars
     * @return void
     */
    public static function addQueriedObjectTaxonomyNameToVars(&$vars)
    {
        $taxonomyTypeAPI = TaxonomyTypeAPIFacade::getInstance();
        $termObjectID = $vars['routing-state']['queried-object-id'];
        $vars['routing-state']['taxonomy-name'] = $taxonomyTypeAPI->getTermTaxonomyName($termObjectID);
    }
}
