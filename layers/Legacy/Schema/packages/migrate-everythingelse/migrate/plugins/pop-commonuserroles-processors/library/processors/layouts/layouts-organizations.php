<?php

class GD_URE_Custom_Module_Processor_ProfileOrganizationLayouts extends GD_URE_Custom_Module_Processor_ProfileOrganizationLayoutsBase
{
    public final const COMPONENT_URE_LAYOUT_PROFILEORGANIZATION_DETAILS = 'ure-layoutuser-profileorganization-details';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_LAYOUT_PROFILEORGANIZATION_DETAILS,
        );
    }
}



