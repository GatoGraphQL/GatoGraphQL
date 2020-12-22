<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Pages\Routing\RouteNatures;
use PoP\ComponentModel\State\ApplicationState;

HooksAPIFacade::getInstance()->addFilter(
    \PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT,
    function ($components) {

        $vars = ApplicationState::getVars();
        switch ($vars['nature']) {
            case RouteNatures::PAGE:
                $component_types = HooksAPIFacade::getInstance()->applyFilters(
                    '\PoPSchema\Pages\ModelInstanceProcessor_Utils:components_from_vars:type:page',
                    []
                );
                if (in_array(POP_MODELINSTANCECOMPONENTTYPE_PAGE_PAGEID, $component_types)) {
                    $page_id = $vars['routing-state']['queried-object-id'];
                    $components[] = TranslationAPIFacade::getInstance()->__('page id:', 'pop-engine').$page_id;
                }
                break;
        }

        return $components;
    }
);
