<?php

class UserStance_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public const MODULE_CONTROLBUTTONGROUP_STANCESTATS_GENERAL = 'controlbuttongroup-stancestats-general';
    public const MODULE_CONTROLBUTTONGROUP_STANCESTATS_ARTICLE = 'controlbuttongroup-stancestats-article';
    public const MODULE_CONTROLBUTTONGROUP_STANCESTATS = 'controlbuttongroup-stancestats';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLBUTTONGROUP_STANCESTATS_GENERAL],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_STANCESTATS_ARTICLE],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_STANCESTATS],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_CONTROLBUTTONGROUP_STANCESTATS_GENERAL:
                $ret[] = [UserStance_Custom_Module_Processor_Codes::class, UserStance_Custom_Module_Processor_Codes::MODULE_CODE_STANCECOUNT_GENERAL];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT];
                break;
        
            case self::MODULE_CONTROLBUTTONGROUP_STANCESTATS_ARTICLE:
                $ret[] = [UserStance_Custom_Module_Processor_Codes::class, UserStance_Custom_Module_Processor_Codes::MODULE_CODE_STANCECOUNT_ARTICLE];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT];
                break;
        
            case self::MODULE_CONTROLBUTTONGROUP_STANCESTATS:
                $ret[] = [UserStance_Custom_Module_Processor_Codes::class, UserStance_Custom_Module_Processor_Codes::MODULE_CODE_STANCECOUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_STANCE_PRO_COUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_COUNT];
                $ret[] = [UserStance_Module_Processor_CustomAnchorControls::class, UserStance_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_STANCE_AGAINST_COUNT];
                break;
        }
        
        return $ret;
    }
}


