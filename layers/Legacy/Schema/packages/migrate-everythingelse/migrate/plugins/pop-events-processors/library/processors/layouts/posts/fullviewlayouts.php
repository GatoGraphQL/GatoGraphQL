<?php

class GD_EM_Module_Processor_CustomFullViewLayouts extends PoP_Module_Processor_CustomFullViewLayoutsBase
{
    public final const MODULE_LAYOUT_FULLVIEW_EVENT = 'layout-fullview-event';
    public final const MODULE_LAYOUT_FULLVIEW_PASTEVENT = 'layout-fullview-pastevent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLVIEW_EVENT],
            [self::class, self::MODULE_LAYOUT_FULLVIEW_PASTEVENT],
        );
    }

    public function getFooterSubmodules(array $module)
    {
        $ret = parent::getFooterSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FULLVIEW_EVENT:
            case self::MODULE_LAYOUT_FULLVIEW_PASTEVENT:
                $ret[] = [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::MODULE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_LAYOUTWRAPPER_USERPOSTINTERACTION];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER];
                // Allow plugins to hook in layouts
                $ret = \PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_CustomFullViewLayouts:footer-modules',
                    $ret,
                    $module
                );
                break;
        }

        return $ret;
    }

    public function getSidebarSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_FULLVIEW_EVENT:
            case self::MODULE_LAYOUT_FULLVIEW_PASTEVENT:
                $sidebars = array(
                    self::MODULE_LAYOUT_FULLVIEW_EVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_EM_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT],
                    self::MODULE_LAYOUT_FULLVIEW_PASTEVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_EM_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT],
                );

                return $sidebars[$module[1]];
        }

        return parent::getSidebarSubmodule($module);
    }
}



