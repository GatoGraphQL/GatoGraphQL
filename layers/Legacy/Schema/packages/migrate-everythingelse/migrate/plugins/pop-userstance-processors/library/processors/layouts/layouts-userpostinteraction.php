<?php

class UserStance_Module_Processor_UserPostInteractionLayouts extends PoP_Module_Processor_UserPostInteractionLayoutsBase
{
    public final const COMPONENT_LAYOUT_USERSTANCEPOSTINTERACTION = 'layout-userstancepostinteraction';
    public final const COMPONENT_USERSTANCE_LAYOUT_USERPOSTINTERACTION = 'userstance-layout-userpostinteraction';
    public final const COMPONENT_USERSTANCE_LAYOUT_USERFULLVIEWINTERACTION = 'userstance-layout-userfullviewinteraction';
    
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_USERSTANCEPOSTINTERACTION],
            [self::class, self::COMPONENT_USERSTANCE_LAYOUT_USERPOSTINTERACTION],
            [self::class, self::COMPONENT_USERSTANCE_LAYOUT_USERFULLVIEWINTERACTION],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_USERSTANCEPOSTINTERACTION:
                $ret[] = [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT];
                break;

            case self::COMPONENT_USERSTANCE_LAYOUT_USERPOSTINTERACTION:
                $ret[] = [UserStance_Module_Processor_CustomControlGroups::class, UserStance_Module_Processor_CustomControlGroups::COMPONENT_USERSTANCE_CONTROLGROUP_USERPOSTINTERACTION];
                break;

            case self::COMPONENT_USERSTANCE_LAYOUT_USERFULLVIEWINTERACTION:
                $ret[] = [UserStance_Module_Processor_CustomControlGroups::class, UserStance_Module_Processor_CustomControlGroups::COMPONENT_USERSTANCE_CONTROLGROUP_USERFULLVIEWINTERACTION];
                break;
        }

        return $ret;
    }
}



