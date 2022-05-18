<?php

define('GD_SIDEBARSECTION_TAG', 'tag');
define('GD_COMPACTSIDEBARSECTION_TAG', 'compact-tag');

class FullTagSidebarSettings
{
    public static function getSidebarSubmodules($section)
    {
        $ret = array();

        switch ($section) {
            case GD_SIDEBARSECTION_TAG:
                $ret[] = [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::MODULE_TAGSOCIALMEDIA];
                $ret[] = [GD_Custom_Module_Processor_TagWidgets::class, GD_Custom_Module_Processor_TagWidgets::MODULE_WIDGETCOMPACT_TAGINFO];
                $ret = \PoP\Root\App::applyFilters('pop_component:sidebar_tag:components', $ret, $section);
                break;

            case GD_COMPACTSIDEBARSECTION_TAG:
                $ret[] = [GD_Custom_Module_Processor_TagMultipleSidebarComponents::class, GD_Custom_Module_Processor_TagMultipleSidebarComponents::MODULE_SIDEBARMULTICOMPONENT_TAGLEFT];
                $ret[] = [GD_Custom_Module_Processor_TagMultipleSidebarComponents::class, GD_Custom_Module_Processor_TagMultipleSidebarComponents::MODULE_SIDEBARMULTICOMPONENT_TAGRIGHT];
                break;
        }
        
        return $ret;
    }
}
