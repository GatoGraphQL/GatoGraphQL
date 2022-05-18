<?php

class GD_URE_Module_Processor_MembersLayouts extends GD_URE_Module_Processor_MembersLayoutsBase
{
    public final const MODULE_URE_LAYOUT_COMMUNITYMEMBERS = 'ure-layout-communitymembers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_LAYOUT_COMMUNITYMEMBERS],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        $layouts = array(
            self::MODULE_URE_LAYOUT_COMMUNITYMEMBERS => [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::MODULE_LAYOUT_POPOVER_USER_AVATAR60],
        );
        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



