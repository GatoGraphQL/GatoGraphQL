<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('pop_module:sidebar_author:components', 'gdCommonuserrolesAuthorsidebarsComponents', 0, 2);
function gdCommonuserrolesAuthorsidebarsComponents($components, $section)
{
    if (PoP_ApplicationProcessors_Utils::addAuthorWidgetDetails()) {
        $extra_components = array();
        if ($section == GD_SIDEBARSECTION_ORGANIZATION) {
            $extra_components[] = [GD_URE_Custom_Module_Processor_Widgets::class, GD_URE_Custom_Module_Processor_Widgets::MODULE_URE_WIDGET_PROFILEORGANIZATION_DETAILS];
        } elseif ($section == GD_SIDEBARSECTION_INDIVIDUAL) {
            $extra_components[] = [GD_URE_Custom_Module_Processor_Widgets::class, GD_URE_Custom_Module_Processor_Widgets::MODULE_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS];
        }

        // Embed the extra components
        array_splice(
            $components, 
            array_search(
                [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_AUTHOR_CONTACT], 
                $components
            ), 
            0, 
            $extra_components
        );
    }

    return $components;
}
