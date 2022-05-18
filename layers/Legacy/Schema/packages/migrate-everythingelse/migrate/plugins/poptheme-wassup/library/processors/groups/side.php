<?php

class PoP_Module_Processor_SideGroups extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_GROUP_SIDE = 'group-side';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GROUP_SIDE],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::MODULE_GROUP_SIDE:
                // Allow GetPoP to only keep the Sections menu
                if ($components = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_SideGroups:modules',
                    array(
                        [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_SIDE_ADDNEW],
                        [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_SIDE_SECTIONS_MULTITARGET],
                        [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_SIDE_MYSECTIONS],
                    ),
                    $component
                )
                ) {
                    $ret = array_merge(
                        $ret,
                        $components
                    );
                }
                break;
        }

        return $ret;
    }
}


