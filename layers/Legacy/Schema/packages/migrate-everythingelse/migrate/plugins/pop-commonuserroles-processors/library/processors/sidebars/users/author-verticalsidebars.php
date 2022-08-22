<?php

class GD_URE_Module_Processor_CustomVerticalAuthorSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_VERTICALSIDEBAR_AUTHOR_ORGANIZATION = 'vertical-sidebar-author-organization';
    public final const COMPONENT_VERTICALSIDEBAR_AUTHOR_INDIVIDUAL = 'vertical-sidebar-author-individual';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VERTICALSIDEBAR_AUTHOR_ORGANIZATION,
            self::COMPONENT_VERTICALSIDEBAR_AUTHOR_INDIVIDUAL,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $sidebarinners = array(
            self::COMPONENT_VERTICALSIDEBAR_AUTHOR_ORGANIZATION => [GD_URE_Module_Processor_CustomVerticalAuthorSidebarInners::class, GD_URE_Module_Processor_CustomVerticalAuthorSidebarInners::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_ORGANIZATION],
            self::COMPONENT_VERTICALSIDEBAR_AUTHOR_INDIVIDUAL => [GD_URE_Module_Processor_CustomVerticalAuthorSidebarInners::class, GD_URE_Module_Processor_CustomVerticalAuthorSidebarInners::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_INDIVIDUAL],
        );

        if ($inner = $sidebarinners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_VERTICALSIDEBAR_AUTHOR_ORGANIZATION:
            case self::COMPONENT_VERTICALSIDEBAR_AUTHOR_INDIVIDUAL:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



