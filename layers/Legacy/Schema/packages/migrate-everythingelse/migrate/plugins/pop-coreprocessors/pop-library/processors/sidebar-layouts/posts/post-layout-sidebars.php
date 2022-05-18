<?php

class PoP_Module_Processor_PostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL = 'layout-postconclusionsidebar-horizontal';
    public final const COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL = 'layout-subjugatedpostconclusionsidebar-horizontal';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL],
            [self::class, self::COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $sidebarinners = array(
            self::COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL => [PoP_Module_Processor_PostLayoutSidebarInners::class, PoP_Module_Processor_PostLayoutSidebarInners::COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL],
            self::COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL => [PoP_Module_Processor_PostLayoutSidebarInners::class, PoP_Module_Processor_PostLayoutSidebarInners::COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



