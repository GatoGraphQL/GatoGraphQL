<?php

class PoP_Module_Processor_CustomVerticalAuthorSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_VERTICALSIDEBAR_AUTHOR_GENERIC = 'vertical-sidebar-author-generic';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBAR_AUTHOR_GENERIC],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $sidebarinners = array(
            self::MODULE_VERTICALSIDEBAR_AUTHOR_GENERIC => [PoP_Module_Processor_CustomVerticalAuthorSidebarInners::class, PoP_Module_Processor_CustomVerticalAuthorSidebarInners::MODULE_VERTICALSIDEBARINNER_AUTHOR_GENERIC],
        );

        if ($inner = $sidebarinners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VERTICALSIDEBAR_AUTHOR_GENERIC:
                $this->appendProp($componentVariation, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



