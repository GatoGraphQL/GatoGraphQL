<?php

\PoP\Root\App::addFilter(
	'PoP_Module_Processor_DropdownButtonControls:addrelatedpost-dropdown:buttons', 
	'popContentpostlinkscreationAddrelatedpostButtons', 
	0
);
function popContentpostlinkscreationAddrelatedpostButtons($buttons)
{
    if (defined('POP_CONTENTPOSTLINKSCREATIONPROCESSORS_INITIALIZED')) {
        if (defined('POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK') && POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK) {
            $buttons[] = [PoP_ContentPostLinksCreation_Module_Processor_PostButtons::class, PoP_ContentPostLinksCreation_Module_Processor_PostButtons::COMPONENT_BUTTON_CONTENTPOSTLINK_CREATE];
        }
    }
    
    return $buttons;
}
