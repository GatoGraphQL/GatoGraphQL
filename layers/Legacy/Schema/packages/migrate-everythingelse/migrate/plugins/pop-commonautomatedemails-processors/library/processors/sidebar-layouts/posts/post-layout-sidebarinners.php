<?php

class PoPTheme_Wassup_AE_Module_Processor_CustomPostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST = 'layout-automatedemails-postsidebarinner-compacthorizontal-post';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST:
                $ret = array_merge(
                    $ret,
                    AE_FullViewSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_POST)
                );
                break;
        }
        
        return $ret;
    }
}



