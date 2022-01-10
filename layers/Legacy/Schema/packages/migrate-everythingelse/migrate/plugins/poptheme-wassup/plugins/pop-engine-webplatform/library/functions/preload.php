<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('\PoP\ComponentModel\Engine:getExtraUris', 'popthemeWassupExtraRoutes');
function popthemeWassupExtraRoutes($extra_routes)
{
    if (!PoPTheme_Wassup_ServerUtils::disablePreloadingPages()) {
        // Load extra URIs for the INITIALFRAMES page
        $vars = ApplicationState::getVars();
        if ($vars['routing']['is-standard'] && $vars['route'] == POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES) {
            $target = $vars['target'];
            if ($routes = HooksAPIFacade::getInstance()->applyFilters(
                'wassup:extra-routes:initialframes:'.$target,
                array()
            )) {
                $extra_routes = array_unique(
                    array_merge(
                        $extra_routes,
                        // array_map(array(RequestUtils::class, 'getPageUri'), $routes)
                        $routes
                    )
                );
            }
        }
    }

    return $extra_routes;
}
