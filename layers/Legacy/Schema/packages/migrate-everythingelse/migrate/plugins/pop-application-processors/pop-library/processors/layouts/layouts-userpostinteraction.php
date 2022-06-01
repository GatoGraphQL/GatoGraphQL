<?php

class Wassup_Module_Processor_UserPostInteractionLayouts extends PoP_Module_Processor_UserPostInteractionLayoutsBase
{
    public final const COMPONENT_LAYOUT_USERPOSTINTERACTION = 'layout-userpostinteraction';
    public final const COMPONENT_LAYOUT_USERHIGHLIGHTPOSTINTERACTION = 'layout-userhighlightpostinteraction';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_USERPOSTINTERACTION],
            [self::class, self::COMPONENT_LAYOUT_USERHIGHLIGHTPOSTINTERACTION],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_USERPOSTINTERACTION:
                // Allow TPPDebate to add the "What do you think about TPP?" before these layouts
                if ($layouts = \PoP\Root\App::applyFilters(
                    'Wassup_Module_Processor_UserPostInteractionLayouts:userpostinteraction:layouts',
                    array(
                        [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_USERPOSTINTERACTION],
                    ),
                    $component
                )) {
                    $ret = array_merge(
                        $ret,
                        $layouts
                    );
                }
                break;

            case self::COMPONENT_LAYOUT_USERHIGHLIGHTPOSTINTERACTION:
                $ret[] = [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT];
                break;
        }

        return $ret;
    }
}



