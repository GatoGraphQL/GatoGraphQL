<?php

class PoP_AddLocations_LocationsProcessors_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'GD_EM_Module_Processor_InputGroupFormComponents:control-layout',
            $this->getControlLayout(...)
        );
    }

    public function getControlLayout($layout)
    {
        return [PoP_Module_Processor_TypeaheadAnchorControls::class, PoP_Module_Processor_TypeaheadAnchorControls::MODULE_ANCHORCONTROL_CREATELOCATION];
    }
}

/**
 * Initialization
 */
new PoP_AddLocations_LocationsProcessors_Hooks();
