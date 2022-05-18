<?php

class GD_URE_Module_Processor_CustomVerticalAuthorSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_VERTICALSIDEBAR_AUTHOR_ORGANIZATION = 'vertical-sidebar-author-organization';
    public final const MODULE_VERTICALSIDEBAR_AUTHOR_INDIVIDUAL = 'vertical-sidebar-author-individual';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBAR_AUTHOR_ORGANIZATION],
            [self::class, self::MODULE_VERTICALSIDEBAR_AUTHOR_INDIVIDUAL],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $sidebarinners = array(
            self::MODULE_VERTICALSIDEBAR_AUTHOR_ORGANIZATION => [GD_URE_Module_Processor_CustomVerticalAuthorSidebarInners::class, GD_URE_Module_Processor_CustomVerticalAuthorSidebarInners::MODULE_VERTICALSIDEBARINNER_AUTHOR_ORGANIZATION],
            self::MODULE_VERTICALSIDEBAR_AUTHOR_INDIVIDUAL => [GD_URE_Module_Processor_CustomVerticalAuthorSidebarInners::class, GD_URE_Module_Processor_CustomVerticalAuthorSidebarInners::MODULE_VERTICALSIDEBARINNER_AUTHOR_INDIVIDUAL],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_VERTICALSIDEBAR_AUTHOR_ORGANIZATION:
            case self::MODULE_VERTICALSIDEBAR_AUTHOR_INDIVIDUAL:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



