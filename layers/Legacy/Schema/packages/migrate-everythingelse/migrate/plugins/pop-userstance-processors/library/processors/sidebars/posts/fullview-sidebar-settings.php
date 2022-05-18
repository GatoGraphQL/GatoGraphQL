<?php

define('GD_SIDEBARSECTION_STANCE', 'stance');
define('GD_COMPACTSIDEBARSECTION_STANCE', 'compact-stance');

class UserStance_FullViewSidebarSettings
{
    public static function getSidebarSubmodules($section)
    {
        $ret = array();

        switch ($section) {
            case GD_SIDEBARSECTION_STANCE:
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGET_POST_AUTHORS];
                break;

            case GD_COMPACTSIDEBARSECTION_STANCE:
                $ret[] = [UserStance_Module_Processor_CustomPostMultipleSidebarComponents::class, UserStance_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_STANCELEFT];
                $ret[] = [UserStance_Module_Processor_CustomPostMultipleSidebarComponents::class, UserStance_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_STANCERIGHT];
                break;
        }
        
        return $ret;
    }
}
