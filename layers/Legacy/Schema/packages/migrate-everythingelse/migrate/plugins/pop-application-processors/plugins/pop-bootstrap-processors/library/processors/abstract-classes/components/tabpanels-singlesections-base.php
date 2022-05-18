<?php

abstract class PoP_Module_Processor_SingleSectionTabPanelComponentsBase extends PoP_Module_Processor_GenericSectionTabPanelComponentsBase
{
    protected function getDefaultActivepanelFormat(array $componentVariation)
    {
        return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLESECTION);
    }
}
