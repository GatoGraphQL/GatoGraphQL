<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter(
	'PoP_Module_Processor_DropdownButtonControls:addrelatedpost-dropdown:buttons', 
	'popLocationpostlinkscreationAddrelatedpostButtons', 
	30
);
function popLocationpostlinkscreationAddrelatedpostButtons($buttons)
{
    if (defined('POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK') && POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK) {
        $buttons[] = [GD_SP_Custom_EM_Module_Processor_Buttons::class, GD_SP_Custom_EM_Module_Processor_Buttons::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE];
    }

    return $buttons;
}
