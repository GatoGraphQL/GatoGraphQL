<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * Implementation of the icons
 */
HooksAPIFacade::getInstance()->addFilter(
    'route:icon', 
    function($icon, $route, $html = true) {
        switch ($route) {
            case POP_CONTENTCREATION_ROUTE_MYCONTENT:
                $fontawesome = 'fa-edit';
                break;
            case POP_CONTENTCREATION_ROUTE_FLAG:
                $fontawesome = 'fa-flag';
                break;
            case POP_CONTENTCREATION_ROUTE_ADDCONTENT:
                $fontawesome = 'fa-plus';
                break;
        }

        return processIcon($icon, $fontawesome, $html);
    }, 
    10, 
    3
);


HooksAPIFacade::getInstance()->addFilter(
    'route:title', 
    function($title, $route) {
        $titles = [
            POP_CONTENTCREATION_ROUTE_MYCONTENT => TranslationAPIFacade::getInstance()->__('My Content', 'pop-contentcreation'),
            POP_CONTENTCREATION_ROUTE_FLAG => TranslationAPIFacade::getInstance()->__('Flag as inappropriate', 'pop-contentcreation'),
            POP_CONTENTCREATION_ROUTE_ADDCONTENT => TranslationAPIFacade::getInstance()->__('Add Content', 'pop-application-processors'),
        ];
        return $titles[$route] ?? $title;
    }, 
    10, 
    2
);

