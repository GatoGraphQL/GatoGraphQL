<?php

class PoP_Module_Processor_CommentUserMentionsLayouts extends PoP_Module_Processor_CommentUserMentionsLayoutsBase
{
    public final const MODULE_LAYOUT_COMMENTUSERMENTIONS = 'layout-commentusermentions';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_COMMENTUSERMENTIONS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_COMMENTUSERMENTIONS:
                $ret[] = [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::COMPONENT_LAYOUT_POPOVER_USER_AVATAR40];
                break;
        }

        return $ret;
    }
}



