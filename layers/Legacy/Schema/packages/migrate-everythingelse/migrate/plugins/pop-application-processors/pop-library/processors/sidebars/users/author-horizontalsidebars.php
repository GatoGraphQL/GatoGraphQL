<?php

class PoP_Module_Processor_CustomHorizontalAuthorSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_HORIZONTALSIDEBAR_AUTHOR_GENERIC = 'horizontal-sidebar-author-generic';
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_HORIZONTALSIDEBAR_AUTHOR_GENERIC],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $sidebarinners = array(
            self::MODULE_HORIZONTALSIDEBAR_AUTHOR_GENERIC => [PoP_Module_Processor_CustomHorizontalAuthorSidebarInners::class, PoP_Module_Processor_CustomHorizontalAuthorSidebarInners::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



