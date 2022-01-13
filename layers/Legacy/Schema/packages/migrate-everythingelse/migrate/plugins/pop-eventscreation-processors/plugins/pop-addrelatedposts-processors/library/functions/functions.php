<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter(
	'PoP_Module_Processor_DropdownButtonControls:addrelatedpost-dropdown:buttons', 
	'gdEmAddrelatedpostButtons', 
	20
);
function gdEmAddrelatedpostButtons($buttons)
{

    // Order here is different than in the main menu, since this order makes more sense for "Responses/additionals":
    // Most likely it will be replied to with a Discussion, Event might be last option by the user, so put it last
    if (defined('POP_EVENTSCREATION_ROUTE_ADDEVENT') && POP_EVENTSCREATION_ROUTE_ADDEVENT) {
        $buttons[] = [GD_Custom_EM_Module_Processor_Buttons::class, GD_Custom_EM_Module_Processor_Buttons::MODULE_BUTTON_EVENT_CREATE];

        if (defined('POP_EVENTLINKSCREATIONPROCESSORS_INITIALIZED')) {
            $buttons[] = [PoP_EventLinksCreation_Module_Processor_Buttons::class, PoP_EventLinksCreation_Module_Processor_Buttons::MODULE_BUTTON_EVENTLINK_CREATE];
        }
    }

    return $buttons;
}
