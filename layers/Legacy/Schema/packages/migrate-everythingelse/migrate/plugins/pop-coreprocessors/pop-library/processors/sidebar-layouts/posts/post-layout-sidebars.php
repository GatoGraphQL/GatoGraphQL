<?php

class PoP_Module_Processor_PostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL = 'layout-postconclusionsidebar-horizontal';
    public final const COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL = 'layout-subjugatedpostconclusionsidebar-horizontal';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
            self::COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $sidebarinners = array(
            self::COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL => [PoP_Module_Processor_PostLayoutSidebarInners::class, PoP_Module_Processor_PostLayoutSidebarInners::COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL],
            self::COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL => [PoP_Module_Processor_PostLayoutSidebarInners::class, PoP_Module_Processor_PostLayoutSidebarInners::COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL],
        );

        if ($inner = $sidebarinners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



