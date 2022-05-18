<?php

class UserStance_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_STANCE = 'layout-postsidebar-vertical-stance';
    public final const MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_STANCE = 'layout-postsidebar-horizontal-stance';
    public final const MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE = 'layout-postsidebarcompact-horizontal-stance';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_STANCE],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_STANCE],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE],

        );
    }

    public function getInnerSubmodule(array $module)
    {
        $sidebarinners = array(
            self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_STANCE => [UserStance_Module_Processor_CustomPostLayoutSidebarInners::class, UserStance_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE],
            self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_STANCE => [UserStance_Module_Processor_CustomPostLayoutSidebarInners::class, UserStance_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE],
            self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE => [UserStance_Module_Processor_CustomPostLayoutSidebarInners::class, UserStance_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE],
        );

        if ($inner = $sidebarinners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_STANCE:
                $this->appendProp($module, $props, 'class', 'vertical stances');
                break;

            case self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_STANCE:
            case self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE:
                $this->appendProp($module, $props, 'class', 'horizontal stances');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



