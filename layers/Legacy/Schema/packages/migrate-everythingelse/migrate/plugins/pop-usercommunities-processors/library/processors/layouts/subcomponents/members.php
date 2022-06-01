<?php

class GD_URE_Module_Processor_MembersLayouts extends GD_URE_Module_Processor_MembersLayoutsBase
{
    public final const COMPONENT_URE_LAYOUT_COMMUNITYMEMBERS = 'ure-layout-communitymembers';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_LAYOUT_COMMUNITYMEMBERS,
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_URE_LAYOUT_COMMUNITYMEMBERS => [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::COMPONENT_LAYOUT_POPOVER_USER_AVATAR60],
        );
        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



