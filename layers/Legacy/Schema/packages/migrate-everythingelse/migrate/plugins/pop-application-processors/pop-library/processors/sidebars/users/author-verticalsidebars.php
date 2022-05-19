<?php

class PoP_Module_Processor_CustomVerticalAuthorSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_VERTICALSIDEBAR_AUTHOR_GENERIC = 'vertical-sidebar-author-generic';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VERTICALSIDEBAR_AUTHOR_GENERIC],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        $sidebarinners = array(
            self::COMPONENT_VERTICALSIDEBAR_AUTHOR_GENERIC => [PoP_Module_Processor_CustomVerticalAuthorSidebarInners::class, PoP_Module_Processor_CustomVerticalAuthorSidebarInners::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_GENERIC],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_VERTICALSIDEBAR_AUTHOR_GENERIC:
                $this->appendProp($component, $props, 'class', 'vertical');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



