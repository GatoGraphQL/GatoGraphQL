<?php

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\ComponentModel\State\ApplicationState;

// If it is a route, then return its title as the Document Title
// Make sure it doesn't change the title for GraphQL persisted queries (/some-query/?view=source)
HooksAPIFacade::getInstance()->addFilter(
    'document_title_parts',
    function ($title) {
        $vars = ApplicationState::getVars();
        if ($vars['routing-state']['is-standard']) {
            $title['title'] = strip_tags(RouteUtils::getRouteTitle($vars['route']));
        }
        return $title;
    }
);
