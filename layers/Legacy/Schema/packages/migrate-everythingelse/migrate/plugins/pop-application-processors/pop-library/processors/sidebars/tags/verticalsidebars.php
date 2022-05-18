<?php

class Wassup_Module_Processor_CustomVerticalTagSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_VERTICALSIDEBAR_TAG = 'vertical-sidebar-tag';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBAR_TAG],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $sidebarinners = array(
            self::MODULE_VERTICALSIDEBAR_TAG => [Wassup_Module_Processor_CustomVerticalTagSidebarInners::class, Wassup_Module_Processor_CustomVerticalTagSidebarInners::MODULE_VERTICALSIDEBARINNER_TAG],
        );

        if ($inner = $sidebarinners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VERTICALSIDEBAR_TAG:
                $this->appendProp($componentVariation, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



