<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Routing\RouteNatures;
use PoP\ComponentModel\State\ApplicationState;

HooksAPIFacade::getInstance()->addFilter(
    \PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT,
    function ($components) {
        $vars = ApplicationState::getVars();
        $nature = $vars['nature'];

        // Properties specific to each nature
        switch ($nature) {
            case RouteNatures::CUSTOMPOST:
                // Single may depend on its post_type and category
                // Post and Event may be different
                // Announcements and Articles (Posts), or Past Event and (Upcoming) Event may be different
                // By default, we check for post type but not for categories
                $component_types = (array)HooksAPIFacade::getInstance()->applyFilters(
                    '\PoP\ComponentModel\ModelInstanceProcessor_Utils:components_from_vars:type:single',
                    array(
                        POP_MODELINSTANCECOMPONENTTYPE_SINGLE_POSTTYPE,
                    )
                );
                if (in_array(POP_MODELINSTANCECOMPONENTTYPE_SINGLE_POSTTYPE, $component_types)) {
                    $customPostType = $vars['routing-state']['queried-object-post-type'];
                    $components[] =
                        TranslationAPIFacade::getInstance()->__('post type:', 'pop-engine')
                        . $customPostType;
                }
                break;
        }

        return $components;
    }
);
