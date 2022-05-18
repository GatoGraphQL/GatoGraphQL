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

    public function getInnerSubmodule(array $componentVariation)
    {
        $sidebarinners = array(
            self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_STANCE => [UserStance_Module_Processor_CustomPostLayoutSidebarInners::class, UserStance_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE],
            self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_STANCE => [UserStance_Module_Processor_CustomPostLayoutSidebarInners::class, UserStance_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE],
            self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE => [UserStance_Module_Processor_CustomPostLayoutSidebarInners::class, UserStance_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE],
        );

        if ($inner = $sidebarinners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBAR_VERTICAL_STANCE:
                $this->appendProp($componentVariation, $props, 'class', 'vertical stances');
                break;

            case self::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_STANCE:
            case self::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE:
                $this->appendProp($componentVariation, $props, 'class', 'horizontal stances');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



