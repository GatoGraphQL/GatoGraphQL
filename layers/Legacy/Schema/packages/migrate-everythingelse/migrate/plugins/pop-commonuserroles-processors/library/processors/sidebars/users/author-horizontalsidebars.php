<?php

class GD_URE_Module_Processor_CustomHorizontalAuthorSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_HORIZONTALSIDEBAR_AUTHOR_ORGANIZATION = 'horizontal-sidebar-author-organization';
    public final const MODULE_HORIZONTALSIDEBAR_AUTHOR_INDIVIDUAL = 'horizontal-sidebar-author-individual';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_HORIZONTALSIDEBAR_AUTHOR_ORGANIZATION],
            [self::class, self::MODULE_HORIZONTALSIDEBAR_AUTHOR_INDIVIDUAL],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $sidebarinners = array(
            self::MODULE_HORIZONTALSIDEBAR_AUTHOR_ORGANIZATION => [GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners::class, GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION],
            self::MODULE_HORIZONTALSIDEBAR_AUTHOR_INDIVIDUAL => [GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners::class, GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL],
        );

        if ($inner = $sidebarinners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



