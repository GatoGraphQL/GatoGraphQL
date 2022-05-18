<?php

class Wassup_Module_Processor_CustomVerticalSingleSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT = 'vertical-sidebar-single-highlight';
    public final const MODULE_VERTICALSIDEBAR_SINGLE_POST = 'vertical-sidebar-single-post';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT],
            [self::class, self::MODULE_VERTICALSIDEBAR_SINGLE_POST],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $sidebarinners = array(
            self::MODULE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT => [Wassup_Module_Processor_CustomVerticalSingleSidebarInners::class, Wassup_Module_Processor_CustomVerticalSingleSidebarInners::MODULE_VERTICALSIDEBARINNER_SINGLE_HIGHLIGHT],
            self::MODULE_VERTICALSIDEBAR_SINGLE_POST => [Wassup_Module_Processor_CustomVerticalSingleSidebarInners::class, Wassup_Module_Processor_CustomVerticalSingleSidebarInners::MODULE_VERTICALSIDEBARINNER_SINGLE_POST],
        );

        if ($inner = $sidebarinners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT:
            case self::MODULE_VERTICALSIDEBAR_SINGLE_POST:
                $this->appendProp($componentVariation, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



