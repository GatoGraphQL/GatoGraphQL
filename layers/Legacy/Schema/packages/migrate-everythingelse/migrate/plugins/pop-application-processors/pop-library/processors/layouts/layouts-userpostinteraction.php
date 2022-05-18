<?php

class Wassup_Module_Processor_UserPostInteractionLayouts extends PoP_Module_Processor_UserPostInteractionLayoutsBase
{
    public final const MODULE_LAYOUT_USERPOSTINTERACTION = 'layout-userpostinteraction';
    public final const MODULE_LAYOUT_USERHIGHLIGHTPOSTINTERACTION = 'layout-userhighlightpostinteraction';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_USERPOSTINTERACTION],
            [self::class, self::MODULE_LAYOUT_USERHIGHLIGHTPOSTINTERACTION],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_USERPOSTINTERACTION:
                // Allow TPPDebate to add the "What do you think about TPP?" before these layouts
                if ($layouts = \PoP\Root\App::applyFilters(
                    'Wassup_Module_Processor_UserPostInteractionLayouts:userpostinteraction:layouts',
                    array(
                        [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_USERPOSTINTERACTION],
                    ),
                    $module
                )) {
                    $ret = array_merge(
                        $ret,
                        $layouts
                    );
                }
                break;

            case self::MODULE_LAYOUT_USERHIGHLIGHTPOSTINTERACTION:
                $ret[] = [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT];
                break;
        }

        return $ret;
    }
}



