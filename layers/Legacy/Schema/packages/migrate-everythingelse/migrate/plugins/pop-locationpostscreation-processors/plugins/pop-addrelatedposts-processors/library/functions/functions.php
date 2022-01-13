<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter(
	'PoP_Module_Processor_DropdownButtonControls:addrelatedpost-dropdown:buttons', 
	'gdCustomEmAddrelatedpostButtons', 
	20
);
function gdCustomEmAddrelatedpostButtons($buttons)
{
    if (defined('POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST') && POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST) {
        $buttons[] = [PoP_LocationPostsCreation_Module_Processor_Buttons::class, PoP_LocationPostsCreation_Module_Processor_Buttons::MODULE_BUTTON_LOCATIONPOST_CREATE];
    }

    return $buttons;
}
