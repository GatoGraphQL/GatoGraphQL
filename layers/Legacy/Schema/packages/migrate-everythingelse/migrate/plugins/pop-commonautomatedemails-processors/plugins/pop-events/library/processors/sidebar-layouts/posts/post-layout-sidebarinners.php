<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_CustomPostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT = 'layout-automatedemails-postsidebarinner-compacthorizontal-event';
    
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:
                $ret = array_merge(
                    $ret,
                    EM_AE_FullViewSidebarSettings::getSidebarSubcomponents(GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_EVENT)
                );
                break;
        }
        
        return $ret;
    }
}



