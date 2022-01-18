<?php

use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;

// If it is a route, then return its title as the Document Title
// Make sure it doesn't change the title for GraphQL persisted queries (/some-query/?view=source)
\PoP\Root\App::addFilter(
    'document_title_parts',
    function ($title) {
        if (\PoP\Root\App::getState(['routing', 'is-generic'])) {
            $title['title'] = strip_tags(RouteUtils::getRouteTitle(\PoP\Root\App::getState('route')));
        }
        return $title;
    }
);
