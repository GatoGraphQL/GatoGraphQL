<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_CustomPostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT = 'layout-automatedemails-postsidebarinner-compacthorizontal-event';
    
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:
                $ret = array_merge(
                    $ret,
                    EM_AE_FullViewSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_EVENT)
                );
                break;
        }
        
        return $ret;
    }
}



