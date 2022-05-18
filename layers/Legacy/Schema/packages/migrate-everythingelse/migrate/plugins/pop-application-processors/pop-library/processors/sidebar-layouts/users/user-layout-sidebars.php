<?php

class PoP_Module_Processor_CustomUserLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_LAYOUT_USERSIDEBAR_VERTICAL = 'layout-usersidebar-vertical';
    public final const MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL = 'layout-usersidebar-horizontal';
    public final const MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL = 'layout-usersidebar-compacthorizontal';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_USERSIDEBAR_VERTICAL],
            [self::class, self::MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL],
            [self::class, self::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $sidebarinners = array(
            self::MODULE_LAYOUT_USERSIDEBAR_VERTICAL => [PoP_Module_Processor_CustomUserLayoutSidebarInners::class, PoP_Module_Processor_CustomUserLayoutSidebarInners::MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL],
            self::MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL => [PoP_Module_Processor_CustomUserLayoutSidebarInners::class, PoP_Module_Processor_CustomUserLayoutSidebarInners::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL],
            self::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL => [PoP_Module_Processor_CustomUserLayoutSidebarInners::class, PoP_Module_Processor_CustomUserLayoutSidebarInners::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL],
        );

        if ($inner = $sidebarinners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_USERSIDEBAR_VERTICAL:
                $this->appendProp($componentVariation, $props, 'class', 'vertical');
                break;

            case self::MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL:
            case self::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL:
                $this->appendProp($componentVariation, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



