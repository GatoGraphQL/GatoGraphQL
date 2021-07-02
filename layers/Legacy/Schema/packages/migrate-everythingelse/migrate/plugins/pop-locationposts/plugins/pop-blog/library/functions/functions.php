<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\Route\RouteUtils;

/**
 * Section Filters
 */
HooksAPIFacade::getInstance()->addFilter('wassup_section_taxonomyterms', 'popLocationpostsSectionTaxonomyterms', 0);
function popLocationpostsSectionTaxonomyterms($section_taxonomyterms)
{
    if (POP_LOCATIONPOSTS_CAT_ALL) {
        $section_taxonomyterms = array_merge_recursive(
            $section_taxonomyterms,
            array(
                POP_LOCATIONPOSTS_TAXONOMY_CATEGORY => array(
                    POP_LOCATIONPOSTS_CAT_ALL,
                ),
            )
        );
    }

    return $section_taxonomyterms;
}

HooksAPIFacade::getInstance()->addFilter('GD_FormInput_ContentSections:taxonomyterms:name', 'popLocationpostsSectionTaxonomytermsName', 10, 3);
function popLocationpostsSectionTaxonomytermsName($name, $taxonomy, $term)
{
    if (POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS) {
        if ($taxonomy == POP_LOCATIONPOSTS_TAXONOMY_CATEGORY) {
            $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
            return RouteUtils::getRouteTitle(POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS);
        }
    }

    return $name;
}
