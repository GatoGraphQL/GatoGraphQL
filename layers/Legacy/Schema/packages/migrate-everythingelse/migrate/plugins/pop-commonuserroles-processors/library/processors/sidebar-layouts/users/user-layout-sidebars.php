<?php

class GD_URE_Module_Processor_CustomUserLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_LAYOUT_USERSIDEBAR_VERTICAL_ORGANIZATION = 'layout-usersidebar-vertical-organization';
    public final const MODULE_LAYOUT_USERSIDEBAR_VERTICAL_INDIVIDUAL = 'layout-usersidebar-vertical-individual';
    public final const MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL_ORGANIZATION = 'layout-usersidebar-horizontal-organization';
    public final const MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL_INDIVIDUAL = 'layout-usersidebar-horizontal-individual';
    public final const MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION = 'layout-usersidebar-compacthorizontal-organization';
    public final const MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL = 'layout-usersidebar-compacthorizontal-individual';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_USERSIDEBAR_VERTICAL_ORGANIZATION],
            [self::class, self::MODULE_LAYOUT_USERSIDEBAR_VERTICAL_INDIVIDUAL],
            [self::class, self::MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL_ORGANIZATION],
            [self::class, self::MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL_INDIVIDUAL],
            [self::class, self::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION],
            [self::class, self::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $sidebarinners = array(
            self::MODULE_LAYOUT_USERSIDEBAR_VERTICAL_ORGANIZATION => [GD_URE_Module_Processor_CustomUserLayoutSidebarInners::class, GD_URE_Module_Processor_CustomUserLayoutSidebarInners::MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL_ORGANIZATION],
            self::MODULE_LAYOUT_USERSIDEBAR_VERTICAL_INDIVIDUAL => [GD_URE_Module_Processor_CustomUserLayoutSidebarInners::class, GD_URE_Module_Processor_CustomUserLayoutSidebarInners::MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL_INDIVIDUAL],
            self::MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL_ORGANIZATION => [GD_URE_Module_Processor_CustomUserLayoutSidebarInners::class, GD_URE_Module_Processor_CustomUserLayoutSidebarInners::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_ORGANIZATION],
            self::MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL_INDIVIDUAL => [GD_URE_Module_Processor_CustomUserLayoutSidebarInners::class, GD_URE_Module_Processor_CustomUserLayoutSidebarInners::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_INDIVIDUAL],
            self::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION => [GD_URE_Module_Processor_CustomUserLayoutSidebarInners::class, GD_URE_Module_Processor_CustomUserLayoutSidebarInners::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_ORGANIZATION],
            self::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL => [GD_URE_Module_Processor_CustomUserLayoutSidebarInners::class, GD_URE_Module_Processor_CustomUserLayoutSidebarInners::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_INDIVIDUAL],
        );

        if ($inner = $sidebarinners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_USERSIDEBAR_VERTICAL_ORGANIZATION:
            case self::MODULE_LAYOUT_USERSIDEBAR_VERTICAL_INDIVIDUAL:
                $this->appendProp($componentVariation, $props, 'class', 'vertical');
                break;

            case self::MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL_ORGANIZATION:
            case self::MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL_INDIVIDUAL:
            case self::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION:
            case self::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL:
                $this->appendProp($componentVariation, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



