<?php

class PoP_Module_Processor_SideGroups extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_GROUP_SIDE = 'group-side';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GROUP_SIDE],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_GROUP_SIDE:
                // Allow GetPoP to only keep the Sections menu
                if ($modules = \PoP\Root\App::getHookManager()->applyFilters(
                    'PoP_Module_Processor_SideGroups:modules',
                    array(
                        [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_SIDE_ADDNEW],
                        [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_SIDE_SECTIONS_MULTITARGET],
                        [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_SIDE_MYSECTIONS],
                    ),
                    $module
                )
                ) {
                    $ret = array_merge(
                        $ret,
                        $modules
                    );
                }
                break;
        }

        return $ret;
    }
}


