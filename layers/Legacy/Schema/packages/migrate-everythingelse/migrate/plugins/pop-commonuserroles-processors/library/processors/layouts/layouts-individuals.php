<?php

class GD_URE_Custom_Module_Processor_ProfileIndividualLayouts extends GD_URE_Custom_Module_Processor_ProfileIndividualLayoutsBase
{
    public final const COMPONENT_URE_LAYOUT_PROFILEINDIVIDUAL_DETAILS = 'ure-layoutuser-profileindividual-details';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_LAYOUT_PROFILEINDIVIDUAL_DETAILS,
        );
    }
}



