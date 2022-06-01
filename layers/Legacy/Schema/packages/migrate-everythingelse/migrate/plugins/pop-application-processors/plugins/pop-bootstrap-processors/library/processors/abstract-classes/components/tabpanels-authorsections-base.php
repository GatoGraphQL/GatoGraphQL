<?php

abstract class PoP_Module_Processor_AuthorSectionTabPanelComponentsBase extends PoP_Module_Processor_GenericSectionTabPanelComponentsBase
{
    protected function getDefaultActivepanelFormat(\PoP\ComponentModel\Component\Component $component)
    {
        return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORSECTION);
    }
}
