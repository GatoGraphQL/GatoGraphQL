<?php

class Wassup_Module_Processor_CustomVerticalTagSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_VERTICALSIDEBAR_TAG = 'vertical-sidebar-tag';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBAR_TAG],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $sidebarinners = array(
            self::MODULE_VERTICALSIDEBAR_TAG => [Wassup_Module_Processor_CustomVerticalTagSidebarInners::class, Wassup_Module_Processor_CustomVerticalTagSidebarInners::MODULE_VERTICALSIDEBARINNER_TAG],
        );

        if ($inner = $sidebarinners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VERTICALSIDEBAR_TAG:
                $this->appendProp($module, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



