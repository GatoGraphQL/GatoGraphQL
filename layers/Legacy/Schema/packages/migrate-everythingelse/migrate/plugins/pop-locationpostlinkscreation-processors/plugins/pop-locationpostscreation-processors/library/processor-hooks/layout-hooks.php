<?php

\PoP\Root\App::addFilter(
	'CommonPages_EM_Module_Processor_ControlButtonGroups:modules', 
	'popLocationpostlinkscreationAddlocationpostButtons'
);
function popLocationpostlinkscreationAddlocationpostButtons($componentVariations)
{
    $componentVariations[] = [CommonPagesEM_Module_Processor_AnchorControls::class, CommonPagesEM_Module_Processor_AnchorControls::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK];
    return $componentVariations;
}
