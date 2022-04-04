<?php

class PoP_Module_Processor_PostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL = 'layout-postconclusionsidebar-horizontal';
    public final const MODULE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL = 'layout-subjugatedpostconclusionsidebar-horizontal';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL],
            [self::class, self::MODULE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $sidebarinners = array(
            self::MODULE_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL => [PoP_Module_Processor_PostLayoutSidebarInners::class, PoP_Module_Processor_PostLayoutSidebarInners::MODULE_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL],
            self::MODULE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL => [PoP_Module_Processor_PostLayoutSidebarInners::class, PoP_Module_Processor_PostLayoutSidebarInners::MODULE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL],
        );

        if ($inner = $sidebarinners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



