<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\Route\RouteUtils;

HooksAPIFacade::getInstance()->addFilter('pop_modulemanager:multilayout_labels', 'gdSpEmMultilayoutLabels');
function gdSpEmMultilayoutLabels($labels)
{
    $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
    $label = '<span class="label label-%s">%s</span>';
    $labels[POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST] = sprintf(
        $label,
        POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST,
        RouteUtils::getRouteTitle(POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS)
    );

    return $labels;
}
