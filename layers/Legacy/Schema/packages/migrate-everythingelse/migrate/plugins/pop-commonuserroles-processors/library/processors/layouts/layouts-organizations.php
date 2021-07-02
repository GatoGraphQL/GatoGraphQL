<?php

class GD_URE_Custom_Module_Processor_ProfileOrganizationLayouts extends GD_URE_Custom_Module_Processor_ProfileOrganizationLayoutsBase
{
    public const MODULE_URE_LAYOUT_PROFILEORGANIZATION_DETAILS = 'ure-layoutuser-profileorganization-details';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_LAYOUT_PROFILEORGANIZATION_DETAILS],
        );
    }
}



