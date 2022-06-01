<?php

class PoP_Module_Processor_PostUserMentionsLayouts extends PoP_Module_Processor_PostUserMentionsLayoutsBase
{
    public final const COMPONENT_LAYOUT_POSTUSERMENTIONS = 'layout-postusermentions';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_POSTUSERMENTIONS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTUSERMENTIONS:
                $ret[] = [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::COMPONENT_LAYOUT_POPOVER_USER_AVATAR40];
                break;
        }

        return $ret;
    }
}



