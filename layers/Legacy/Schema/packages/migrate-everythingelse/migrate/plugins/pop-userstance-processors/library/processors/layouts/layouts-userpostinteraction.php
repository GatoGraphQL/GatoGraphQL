<?php

class UserStance_Module_Processor_UserPostInteractionLayouts extends PoP_Module_Processor_UserPostInteractionLayoutsBase
{
    public const MODULE_LAYOUT_USERSTANCEPOSTINTERACTION = 'layout-userstancepostinteraction';
    public const MODULE_USERSTANCE_LAYOUT_USERPOSTINTERACTION = 'userstance-layout-userpostinteraction';
    public const MODULE_USERSTANCE_LAYOUT_USERFULLVIEWINTERACTION = 'userstance-layout-userfullviewinteraction';
    
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_USERSTANCEPOSTINTERACTION],
            [self::class, self::MODULE_USERSTANCE_LAYOUT_USERPOSTINTERACTION],
            [self::class, self::MODULE_USERSTANCE_LAYOUT_USERFULLVIEWINTERACTION],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_USERSTANCEPOSTINTERACTION:
                $ret[] = [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT];
                break;

            case self::MODULE_USERSTANCE_LAYOUT_USERPOSTINTERACTION:
                $ret[] = [UserStance_Module_Processor_CustomControlGroups::class, UserStance_Module_Processor_CustomControlGroups::MODULE_USERSTANCE_CONTROLGROUP_USERPOSTINTERACTION];
                break;

            case self::MODULE_USERSTANCE_LAYOUT_USERFULLVIEWINTERACTION:
                $ret[] = [UserStance_Module_Processor_CustomControlGroups::class, UserStance_Module_Processor_CustomControlGroups::MODULE_USERSTANCE_CONTROLGROUP_USERFULLVIEWINTERACTION];
                break;
        }

        return $ret;
    }
}



