<?php

class PoP_Module_Processor_CustomVerticalAuthorSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_VERTICALSIDEBAR_AUTHOR_GENERIC = 'vertical-sidebar-author-generic';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBAR_AUTHOR_GENERIC],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $sidebarinners = array(
            self::MODULE_VERTICALSIDEBAR_AUTHOR_GENERIC => [PoP_Module_Processor_CustomVerticalAuthorSidebarInners::class, PoP_Module_Processor_CustomVerticalAuthorSidebarInners::MODULE_VERTICALSIDEBARINNER_AUTHOR_GENERIC],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_VERTICALSIDEBAR_AUTHOR_GENERIC:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



