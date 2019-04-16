<?php

// If it is a route, then return its title as the Document Title
\PoP\CMS\HooksAPI_Factory::getInstance()->addFilter(
    'document_title_parts',
    function($title) {

        $vars = \PoP\Engine\Engine_Vars::getVars();
        if ($vars['routing-state']['is-standard']) {
            $title['title'] = strip_tags(\PoP\Engine\Utils::getRouteTitle($vars['route']));
        }

        return $title;
    }
);