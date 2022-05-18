<?php

class PoP_ContentPostLinks_Module_Processor_CustomVerticalSingleSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_VERTICALSIDEBAR_SINGLE_LINK = 'vertical-sidebar-single-link';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBAR_SINGLE_LINK],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $sidebarinners = array(
            self::MODULE_VERTICALSIDEBAR_SINGLE_LINK => [PoP_ContentPostLinks_Module_Processor_CustomVerticalSingleSidebarInners::class, PoP_ContentPostLinks_Module_Processor_CustomVerticalSingleSidebarInners::MODULE_VERTICALSIDEBARINNER_SINGLE_LINK],
        );

        if ($inner = $sidebarinners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VERTICALSIDEBAR_SINGLE_LINK:
                $this->appendProp($module, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



