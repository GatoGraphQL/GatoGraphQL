<?php

abstract class PoP_Module_Processor_FullUserTitleLayoutsBase extends PoP_Module_Processor_FullObjectTitleLayoutsBase
{
    public function getTitleField(array $componentVariation, array &$props)
    {
        return 'displayName';
    }
    
    public function getTitleConditionField(array $componentVariation, array &$props)
    {
        return 'isProfile';
    }
}
