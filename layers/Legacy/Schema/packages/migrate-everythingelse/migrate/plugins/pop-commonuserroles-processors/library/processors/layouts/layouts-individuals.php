<?php

class GD_URE_Custom_Module_Processor_ProfileIndividualLayouts extends GD_URE_Custom_Module_Processor_ProfileIndividualLayoutsBase
{
    public final const MODULE_URE_LAYOUT_PROFILEINDIVIDUAL_DETAILS = 'ure-layoutuser-profileindividual-details';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_LAYOUT_PROFILEINDIVIDUAL_DETAILS],
        );
    }
}



