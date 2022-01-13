<?php
use PoP\Engine\Route\RouteUtils;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

\PoP\Root\App::addFilter('wassup_section_taxonomyterms', 'popEmSectionTaxonomyterms', 0);
function popEmSectionTaxonomyterms($section_taxonomyterms)
{
    if (POP_EVENTS_CAT_ALL) {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $section_taxonomyterms = array_merge_recursive(
            $section_taxonomyterms,
            array(
                $eventTypeAPI->getEventCategoryTaxonomy() => array(
                    POP_EVENTS_CAT_ALL,
                ),
            )
        );
    }

    return $section_taxonomyterms;
}

\PoP\Root\App::addFilter('GD_FormInput_ContentSections:taxonomyterms:name', 'popEmSectionTaxonomytermsName', 10, 3);
function popEmSectionTaxonomytermsName($name, $taxonomy, $term)
{
    if (POP_EVENTS_ROUTE_EVENTS) {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        if ($taxonomy == $eventTypeAPI->getEventCategoryTaxonomy()) {
            return RouteUtils::getRouteTitle(POP_EVENTS_ROUTE_EVENTS);
        }
    }

    return $name;
}
