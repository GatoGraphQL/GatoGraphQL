<?php

class UserStance_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_CONTROLBUTTONGROUP_STANCESTATS_GENERAL = 'controlbuttongroup-stancestats-general';
    public final const COMPONENT_CONTROLBUTTONGROUP_STANCESTATS_ARTICLE = 'controlbuttongroup-stancestats-article';
    public final const COMPONENT_CONTROLBUTTONGROUP_STANCESTATS = 'controlbuttongroup-stancestats';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTROLBUTTONGROUP_STANCESTATS_GENERAL,
            self::COMPONENT_CONTROLBUTTONGROUP_STANCESTATS_ARTICLE,
            self::COMPONENT_CONTROLBUTTONGROUP_STANCESTATS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_CONTROLBUTTONGROUP_STANCESTATS_GENERAL:
                $ret[] = [UserStance_Custom_Module_Processor_Codes::class, UserStance_Custom_Module_Processor_Codes::COMPONENT_CODE_STANCECOUNT_GENERAL];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT];
                break;
        
            case self::COMPONENT_CONTROLBUTTONGROUP_STANCESTATS_ARTICLE:
                $ret[] = [UserStance_Custom_Module_Processor_Codes::class, UserStance_Custom_Module_Processor_Codes::COMPONENT_CODE_STANCECOUNT_ARTICLE];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT];
                break;
        
            case self::COMPONENT_CONTROLBUTTONGROUP_STANCESTATS:
                $ret[] = [UserStance_Custom_Module_Processor_Codes::class, UserStance_Custom_Module_Processor_Codes::COMPONENT_CODE_STANCECOUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_STANCE_PRO_COUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_COUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_COUNT];
                break;
        }
        
        return $ret;
    }
}


