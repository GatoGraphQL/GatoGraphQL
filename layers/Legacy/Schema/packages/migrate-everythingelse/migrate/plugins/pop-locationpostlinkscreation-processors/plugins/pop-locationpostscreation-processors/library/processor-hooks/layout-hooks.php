<?php

\PoP\Root\App::addFilter(
	'CommonPages_EM_Module_Processor_ControlButtonGroups:modules', 
	'popLocationpostlinkscreationAddlocationpostButtons'
);
function popLocationpostlinkscreationAddlocationpostButtons($components)
{
    $components[] = [CommonPagesEM_Module_Processor_AnchorControls::class, CommonPagesEM_Module_Processor_AnchorControls::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK];
    return $components;
}
