<?php

class PoP_AddHighlights_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_QUICKLINKBUTTONGROUP_HIGHLIGHTEDIT = 'quicklinkbuttongroup-highlightedit';
    public final const MODULE_QUICKLINKBUTTONGROUP_HIGHLIGHTVIEW = 'quicklinkbuttongroup-highlightview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_HIGHLIGHTEDIT],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_HIGHLIGHTVIEW],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_QUICKLINKBUTTONGROUP_HIGHLIGHTEDIT:
                $ret[] = [PoP_AddHighlights_Module_Processor_Buttons::class, PoP_AddHighlights_Module_Processor_Buttons::MODULE_BUTTON_HIGHLIGHTEDIT];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_HIGHLIGHTVIEW:
                $ret[] = [Wassup_Module_Processor_ButtonWrappers::class, Wassup_Module_Processor_ButtonWrappers::MODULE_BUTTONWRAPPER_HIGHLIGHTVIEW];
                break;
        }
        
        return $ret;
    }
}


