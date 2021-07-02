<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter(
	'PoP_Module_Processor_DropdownButtonControls:addrelatedpost-dropdown:buttons', 
	'popPostscreationAddrelatedpostButtons', 
	0
);
function popPostscreationAddrelatedpostButtons($buttons)
{
    if (defined('POP_POSTSCREATION_ROUTE_ADDPOST') && POP_POSTSCREATION_ROUTE_ADDPOST) {
        $buttons[] = [Wassup_Module_Processor_PostButtons::class, Wassup_Module_Processor_PostButtons::MODULE_BUTTON_POST_CREATE];
    }
    
    return $buttons;
}
