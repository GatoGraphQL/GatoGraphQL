<?php

abstract class PoP_Module_Processor_FullUserTitleLayoutsBase extends PoP_Module_Processor_FullObjectTitleLayoutsBase
{
    public function getTitleField(array $component, array &$props)
    {
        return 'displayName';
    }
    
    public function getTitleConditionField(array $component, array &$props)
    {
        return 'isProfile';
    }
}
