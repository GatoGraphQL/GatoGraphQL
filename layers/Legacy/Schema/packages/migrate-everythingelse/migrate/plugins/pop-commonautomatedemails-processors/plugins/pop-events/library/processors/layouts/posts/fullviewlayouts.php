<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_FullViewLayouts extends PoP_Module_Processor_CustomFullViewLayoutsBase
{
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT = 'layout-automatedemails-fullview-event';
    
    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT],
        );
    }

    public function getSidebarSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT:
                $sidebars = array(
                    self::MODULE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT => [PoPTheme_Wassup_EM_AE_Module_Processor_CustomPostLayoutSidebars::class, PoPTheme_Wassup_EM_AE_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT],
                );

                return $sidebars[$module[1]];
        }

        return parent::getSidebarSubmodule($module);
    }
}



