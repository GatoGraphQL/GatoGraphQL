<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class UserStance_Module_Processor_CustomControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_CONTROLGROUP_STANCESTATS = 'controlgroup-stancestats';
    public final const MODULE_CONTROLGROUP_MYSTANCELIST = 'controlgroup-mystancelist';
    public final const MODULE_USERSTANCE_CONTROLGROUP_USERPOSTINTERACTION = 'userstance-controlgroup-userpostinteraction';
    public final const MODULE_USERSTANCE_CONTROLGROUP_USERFULLVIEWINTERACTION = 'userstance-controlgroup-userfullviewinteraction';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLGROUP_STANCESTATS],
            [self::class, self::MODULE_CONTROLGROUP_MYSTANCELIST],
            [self::class, self::MODULE_USERSTANCE_CONTROLGROUP_USERPOSTINTERACTION],
            [self::class, self::MODULE_USERSTANCE_CONTROLGROUP_USERFULLVIEWINTERACTION],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CONTROLGROUP_STANCESTATS:
                $ret[] = [UserStance_Module_Processor_CustomControlButtonGroups::class, UserStance_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_STANCESTATS_GENERAL];
                $ret[] = [UserStance_Module_Processor_CustomControlButtonGroups::class, UserStance_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_STANCESTATS_ARTICLE];
                $ret[] = [UserStance_Module_Processor_CustomControlButtonGroups::class, UserStance_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_STANCESTATS];
                break;

            case self::MODULE_CONTROLGROUP_MYSTANCELIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                break;

            case self::MODULE_USERSTANCE_CONTROLGROUP_USERPOSTINTERACTION:
                $ret[] = [PoP_AddHighlights_Module_Processor_PostButtons::class, PoP_AddHighlights_Module_Processor_PostButtons::MODULE_BUTTON_HIGHLIGHT_CREATEBTN];
                $ret[] = [PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups::class, PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_ADDRELATEDPOST];
                break;

            case self::MODULE_USERSTANCE_CONTROLGROUP_USERFULLVIEWINTERACTION:
                $ret[] = [UserStance_Module_Processor_WidgetWrappers::class, UserStance_Module_Processor_WidgetWrappers::MODULE_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE];
                $ret[] = [PoP_AddHighlights_Module_Processor_PostButtons::class, PoP_AddHighlights_Module_Processor_PostButtons::MODULE_BUTTON_HIGHLIGHT_CREATEBTN];
                $ret[] = [PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups::class, PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_ADDRELATEDPOST];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_CONTROLGROUP_STANCESTATS:
                // Make them collapsible, with a control expanding them by looking for class "collapse"
                $this->appendProp([UserStance_Module_Processor_CustomControlButtonGroups::class, UserStance_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_STANCESTATS_ARTICLE], $props, 'class', 'collapse');
                $this->appendProp([UserStance_Module_Processor_CustomControlButtonGroups::class, UserStance_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_STANCESTATS], $props, 'class', 'collapse');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


