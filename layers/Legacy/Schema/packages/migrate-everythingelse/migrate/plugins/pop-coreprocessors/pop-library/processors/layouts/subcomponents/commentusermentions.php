<?php

class PoP_Module_Processor_CommentUserMentionsLayouts extends PoP_Module_Processor_CommentUserMentionsLayoutsBase
{
    public final const COMPONENT_LAYOUT_COMMENTUSERMENTIONS = 'layout-commentusermentions';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_COMMENTUSERMENTIONS,
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_COMMENTUSERMENTIONS:
                $ret[] = [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::COMPONENT_LAYOUT_POPOVER_USER_AVATAR40];
                break;
        }

        return $ret;
    }
}



