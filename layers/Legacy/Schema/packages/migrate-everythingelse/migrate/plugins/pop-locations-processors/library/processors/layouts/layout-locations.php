<?php

class GD_EM_Module_Processor_LocationLayouts extends GD_EM_Module_Processor_LocationLayoutsBase
{
    public final const COMPONENT_EM_LAYOUT_LOCATIONS = 'em-layout-locations';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_LAYOUT_LOCATIONS,
        );
    }
}



