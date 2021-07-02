<?php

class GD_URE_Module_Processor_MembersLayouts extends GD_URE_Module_Processor_MembersLayoutsBase
{
    public const MODULE_URE_LAYOUT_COMMUNITYMEMBERS = 'ure-layout-communitymembers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_LAYOUT_COMMUNITYMEMBERS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_URE_LAYOUT_COMMUNITYMEMBERS => [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::MODULE_LAYOUT_POPOVER_USER_AVATAR60],
        );
        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



