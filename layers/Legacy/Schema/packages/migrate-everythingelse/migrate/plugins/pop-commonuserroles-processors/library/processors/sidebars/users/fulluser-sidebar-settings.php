<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('GD_SIDEBARSECTION_ORGANIZATION', 'organization');
define('GD_SIDEBARSECTION_INDIVIDUAL', 'individual');

define('GD_COMPACTSIDEBARSECTION_ORGANIZATION', 'compact-organization');
define('GD_COMPACTSIDEBARSECTION_INDIVIDUAL', 'compact-individual');

class URE_FullUserSidebarSettings
{
    public static function getSidebarSubmodules($section)
    {
        $ret = array();

        switch ($section) {
            case GD_SIDEBARSECTION_ORGANIZATION:
            case GD_SIDEBARSECTION_INDIVIDUAL:
                if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_UserPhotoLayouts::class, PoP_Module_Processor_UserPhotoLayouts::MODULE_LAYOUT_AUTHOR_USERPHOTO];
                }
                $ret[] = [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::MODULE_USERSOCIALMEDIA];
                if (defined('POP_SOCIALNETWORKPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_SocialNetwork_Module_Processor_UserViewComponentButtons::class, PoP_SocialNetwork_Module_Processor_UserViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL];
                }
                if (PoP_ApplicationProcessors_Utils::addAuthorWidgetDetails()) {
                    $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_AUTHOR_CONTACT];
                    $ret[] = [PoP_Locations_Module_Processor_SidebarComponents::class, PoP_Locations_Module_Processor_SidebarComponents::MODULE_EM_WIDGET_USERLOCATIONSMAP];
                }
                $ret = HooksAPIFacade::getInstance()->applyFilters('pop_module:sidebar_author:components', $ret, $section);
                break;

            case GD_COMPACTSIDEBARSECTION_ORGANIZATION:
                $ret[] = [GD_Custom_Module_Processor_UserMultipleSidebarComponents::class, GD_Custom_Module_Processor_UserMultipleSidebarComponents::MODULE_SIDEBARMULTICOMPONENT_AVATAR];
                $ret[] = [GD_URE_Custom_Module_Processor_UserMultipleSidebarComponents::class, GD_URE_Custom_Module_Processor_UserMultipleSidebarComponents::MODULE_SIDEBARMULTICOMPONENT_ORGANIZATION];
                break;

            case GD_COMPACTSIDEBARSECTION_INDIVIDUAL:
                $ret[] = [GD_Custom_Module_Processor_UserMultipleSidebarComponents::class, GD_Custom_Module_Processor_UserMultipleSidebarComponents::MODULE_SIDEBARMULTICOMPONENT_AVATAR];
                $ret[] = [GD_URE_Custom_Module_Processor_UserMultipleSidebarComponents::class, GD_URE_Custom_Module_Processor_UserMultipleSidebarComponents::MODULE_SIDEBARMULTICOMPONENT_INDIVIDUAL];
                break;
        }
        
        return $ret;
    }
}
