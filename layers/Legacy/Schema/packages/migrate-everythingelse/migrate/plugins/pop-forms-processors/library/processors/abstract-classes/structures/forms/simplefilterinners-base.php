<?php

abstract class PoP_Module_Processor_SimpleFilterInnersBase extends PoP_Module_Processor_FilterInnersBase
{

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function getSubmitbtnComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SEARCH];
    }
}
