<?php

class GD_EM_Module_Processor_CustomVerticalSingleSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_VERTICALSIDEBAR_SINGLE_EVENT = 'vertical-sidebar-single-event';
    public final const MODULE_VERTICALSIDEBAR_SINGLE_PASTEVENT = 'vertical-sidebar-single-pastevent';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBAR_SINGLE_EVENT],
            [self::class, self::MODULE_VERTICALSIDEBAR_SINGLE_PASTEVENT],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $sidebarinners = array(
            self::MODULE_VERTICALSIDEBAR_SINGLE_EVENT => [GD_EM_Module_Processor_CustomVerticalSingleSidebarInners::class, GD_EM_Module_Processor_CustomVerticalSingleSidebarInners::MODULE_VERTICALSIDEBARINNER_SINGLE_EVENT],
            self::MODULE_VERTICALSIDEBAR_SINGLE_PASTEVENT => [GD_EM_Module_Processor_CustomVerticalSingleSidebarInners::class, GD_EM_Module_Processor_CustomVerticalSingleSidebarInners::MODULE_VERTICALSIDEBARINNER_SINGLE_PASTEVENT],
        );

        if ($inner = $sidebarinners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VERTICALSIDEBAR_SINGLE_EVENT:
            case self::MODULE_VERTICALSIDEBAR_SINGLE_PASTEVENT:
                $this->appendProp($module, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



