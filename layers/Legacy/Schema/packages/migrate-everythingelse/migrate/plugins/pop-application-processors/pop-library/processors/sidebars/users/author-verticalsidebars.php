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

    public function getInnerSubmodule(array $module)
    {
        $sidebarinners = array(
            self::MODULE_VERTICALSIDEBAR_AUTHOR_GENERIC => [PoP_Module_Processor_CustomVerticalAuthorSidebarInners::class, PoP_Module_Processor_CustomVerticalAuthorSidebarInners::MODULE_VERTICALSIDEBARINNER_AUTHOR_GENERIC],
        );

        if ($inner = $sidebarinners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VERTICALSIDEBAR_AUTHOR_GENERIC:
                $this->appendProp($module, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



