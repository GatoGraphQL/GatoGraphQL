<?php

abstract class PoP_Module_Processor_SimpleFilterInnersBase extends PoP_Module_Processor_FilterInnersBase
{

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function getSubmitbtnModule(array $module)
    {
        return [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_SEARCH];
    }
}
