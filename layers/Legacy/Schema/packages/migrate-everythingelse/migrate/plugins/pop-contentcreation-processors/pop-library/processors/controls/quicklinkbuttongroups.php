<?php

class GD_ContentCreation_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTEDIT = 'quicklinkbuttongroup-postedit';
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTVIEW = 'quicklinkbuttongroup-postview';
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTPREVIEW = 'quicklinkbuttongroup-postpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_POSTEDIT],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_POSTVIEW],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_POSTPREVIEW],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::MODULE_QUICKLINKBUTTONGROUP_POSTEDIT:
                $ret[] = [GD_ContentCreation_Module_Processor_Buttons::class, GD_ContentCreation_Module_Processor_Buttons::MODULE_BUTTON_POSTEDIT];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_POSTVIEW:
                $ret[] = [GD_ContentCreation_Module_Processor_ButtonWrappers::class, GD_ContentCreation_Module_Processor_ButtonWrappers::MODULE_BUTTONWRAPPER_POSTVIEW];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_POSTPREVIEW:
                $ret[] = [GD_ContentCreation_Module_Processor_ButtonWrappers::class, GD_ContentCreation_Module_Processor_ButtonWrappers::MODULE_BUTTONWRAPPER_POSTPREVIEW];
                break;
        }
        
        return $ret;
    }
}


