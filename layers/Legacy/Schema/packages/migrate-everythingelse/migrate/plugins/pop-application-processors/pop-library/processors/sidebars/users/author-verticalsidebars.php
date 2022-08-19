<?php

class PoP_Module_Processor_CustomVerticalAuthorSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_VERTICALSIDEBAR_AUTHOR_GENERIC = 'vertical-sidebar-author-generic';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VERTICALSIDEBAR_AUTHOR_GENERIC,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $sidebarinners = array(
            self::COMPONENT_VERTICALSIDEBAR_AUTHOR_GENERIC => [PoP_Module_Processor_CustomVerticalAuthorSidebarInners::class, PoP_Module_Processor_CustomVerticalAuthorSidebarInners::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_GENERIC],
        );

        if ($inner = $sidebarinners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_VERTICALSIDEBAR_AUTHOR_GENERIC:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



