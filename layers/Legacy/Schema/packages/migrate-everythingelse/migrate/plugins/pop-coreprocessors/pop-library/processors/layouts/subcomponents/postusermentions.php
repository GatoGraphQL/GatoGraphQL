<?php

class PoP_Module_Processor_PostUserMentionsLayouts extends PoP_Module_Processor_PostUserMentionsLayoutsBase
{
    public final const COMPONENT_LAYOUT_POSTUSERMENTIONS = 'layout-postusermentions';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_POSTUSERMENTIONS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTUSERMENTIONS:
                $ret[] = [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::COMPONENT_LAYOUT_POPOVER_USER_AVATAR40];
                break;
        }

        return $ret;
    }
}



