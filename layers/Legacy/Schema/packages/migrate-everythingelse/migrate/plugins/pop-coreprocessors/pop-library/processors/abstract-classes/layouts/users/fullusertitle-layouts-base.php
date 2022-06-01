<?php

abstract class PoP_Module_Processor_FullUserTitleLayoutsBase extends PoP_Module_Processor_FullObjectTitleLayoutsBase
{
    public function getTitleField(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'displayName';
    }
    
    public function getTitleConditionField(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'isProfile';
    }
}
