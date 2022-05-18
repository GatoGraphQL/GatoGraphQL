<?php

class PoP_Module_Processor_CustomMenuDataloads extends PoP_Module_Processor_MenuDataloadsBase
{
    public final const COMPONENT_DATALOAD_MENU_SIDEBAR_ABOUT = 'dataload-menu-sidebar-about';
    public final const COMPONENT_DATALOAD_MENU_TOPNAV_USERLOGGEDIN = 'dataload-menu-top-userloggedin';
    public final const COMPONENT_DATALOAD_MENU_TOPNAV_USERNOTLOGGEDIN = 'dataload-menu-top-usernotloggedin';
    public final const COMPONENT_DATALOAD_MENU_TOPNAV_ABOUT = 'dataload-menu-top-about';
    public final const COMPONENT_DATALOAD_MENU_TOP_ADDNEW = 'dataload-menu-top-addnew';
    public final const COMPONENT_DATALOAD_MENU_HOME_USERNOTLOGGEDIN = 'dataload-menu-home-usernotloggedin';
    public final const COMPONENT_DATALOAD_MENU_SIDE_ADDNEW = 'dataload-menu-side-addnew';
    public final const COMPONENT_DATALOAD_MENU_SIDE_SECTIONS = 'dataload-menu-side-sections';
    public final const COMPONENT_DATALOAD_MENU_SIDE_SECTIONS_MULTITARGET = 'dataload-menu-side-sections-multitarget';
    public final const COMPONENT_DATALOAD_MENU_SIDE_MYSECTIONS = 'dataload-menu-side-mysections';
    public final const COMPONENT_DATALOAD_MENU_BODY_ADDCONTENT = 'dataload-menu-body-addcontent';
    public final const COMPONENT_DATALOAD_MENU_BODY_SECTIONS = 'dataload-menu-body-sections';
    public final const COMPONENT_DATALOAD_MENU_BODY_MYSECTIONS = 'dataload-menu-body-mysections';
    public final const COMPONENT_DATALOAD_MENU_BODY_ABOUT = 'dataload-menu-body-about';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_MENU_SIDEBAR_ABOUT],
            [self::class, self::COMPONENT_DATALOAD_MENU_TOPNAV_USERLOGGEDIN],
            [self::class, self::COMPONENT_DATALOAD_MENU_TOPNAV_USERNOTLOGGEDIN],
            [self::class, self::COMPONENT_DATALOAD_MENU_TOPNAV_ABOUT],
            [self::class, self::COMPONENT_DATALOAD_MENU_TOP_ADDNEW],
            [self::class, self::COMPONENT_DATALOAD_MENU_HOME_USERNOTLOGGEDIN],
            [self::class, self::COMPONENT_DATALOAD_MENU_SIDE_ADDNEW],
            [self::class, self::COMPONENT_DATALOAD_MENU_SIDE_SECTIONS],
            [self::class, self::COMPONENT_DATALOAD_MENU_SIDE_SECTIONS_MULTITARGET],
            [self::class, self::COMPONENT_DATALOAD_MENU_SIDE_MYSECTIONS],
            [self::class, self::COMPONENT_DATALOAD_MENU_BODY_ADDCONTENT],
            [self::class, self::COMPONENT_DATALOAD_MENU_BODY_SECTIONS],
            [self::class, self::COMPONENT_DATALOAD_MENU_BODY_MYSECTIONS],
            [self::class, self::COMPONENT_DATALOAD_MENU_BODY_ABOUT],
        );
    }

    // function getRelevantRoute(array $component, array &$props) {

    //     $routes = array(
    //         self::COMPONENT_DATALOAD_MENU_BODY_ABOUT => POP_COMMONPAGES_ROUTE_ABOUT,
    //         self::COMPONENT_DATALOAD_MENU_BODY_ADDCONTENT => POP_CONTENTCREATION_ROUTE_ADDCONTENT,
    //     );
    //     return $routes[$component[1]] ?? parent::getRelevantRoute($component, $props);
    // }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::COMPONENT_DATALOAD_MENU_SIDEBAR_ABOUT => [PoP_Module_Processor_CustomMenuSidebars::class, PoP_Module_Processor_CustomMenuSidebars::COMPONENT_SIDEBAR_MENU_ABOUT],
            self::COMPONENT_DATALOAD_MENU_TOPNAV_USERLOGGEDIN => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::COMPONENT_INDENTMENU],
            self::COMPONENT_DATALOAD_MENU_TOPNAV_USERNOTLOGGEDIN => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::COMPONENT_INDENTMENU],
            self::COMPONENT_DATALOAD_MENU_TOPNAV_ABOUT => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::COMPONENT_INDENTMENU],
            self::COMPONENT_DATALOAD_MENU_TOP_ADDNEW => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::COMPONENT_INDENTMENU],
            self::COMPONENT_DATALOAD_MENU_HOME_USERNOTLOGGEDIN => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::COMPONENT_INDENTMENU],
            self::COMPONENT_DATALOAD_MENU_SIDE_ADDNEW => [PoP_Module_Processor_Menus::class, PoP_Module_Processor_Menus::COMPONENT_DROPDOWNBUTTONMENU_SIDE],
            self::COMPONENT_DATALOAD_MENU_SIDE_SECTIONS => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::COMPONENT_INDENTMENU],
            self::COMPONENT_DATALOAD_MENU_SIDE_SECTIONS_MULTITARGET => [PoP_Module_Processor_Menus::class, PoP_Module_Processor_Menus::COMPONENT_MULTITARGETINDENTMENU],
            self::COMPONENT_DATALOAD_MENU_SIDE_MYSECTIONS => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::COMPONENT_INDENTMENU],
            self::COMPONENT_DATALOAD_MENU_BODY_ADDCONTENT => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::COMPONENT_INDENTMENU],
            self::COMPONENT_DATALOAD_MENU_BODY_SECTIONS => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::COMPONENT_INDENTMENU],
            self::COMPONENT_DATALOAD_MENU_BODY_MYSECTIONS => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::COMPONENT_INDENTMENU],
            self::COMPONENT_DATALOAD_MENU_BODY_ABOUT => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::COMPONENT_INDENTMENU],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getMenu(array $component)
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_MENU_SIDEBAR_ABOUT => GD_MENU_SIDEBAR_ABOUT,
            self::COMPONENT_DATALOAD_MENU_TOPNAV_USERLOGGEDIN => GD_MENU_TOPNAV_USERLOGGEDIN,
            self::COMPONENT_DATALOAD_MENU_TOPNAV_USERNOTLOGGEDIN => GD_MENU_TOPNAV_USERNOTLOGGEDIN,
            self::COMPONENT_DATALOAD_MENU_TOPNAV_ABOUT => GD_MENU_TOPNAV_ABOUT,
            self::COMPONENT_DATALOAD_MENU_TOP_ADDNEW => GD_MENU_TOPNAV_ADDCONTENT,
            self::COMPONENT_DATALOAD_MENU_HOME_USERNOTLOGGEDIN => GD_MENU_TOPNAV_USERNOTLOGGEDIN,
            self::COMPONENT_DATALOAD_MENU_SIDE_ADDNEW => GD_MENU_TOPNAV_ADDCONTENT,
            self::COMPONENT_DATALOAD_MENU_SIDE_SECTIONS => GD_MENU_SIDENAV_SECTIONS,
            self::COMPONENT_DATALOAD_MENU_SIDE_SECTIONS_MULTITARGET => GD_MENU_SIDENAV_SECTIONS,
            self::COMPONENT_DATALOAD_MENU_SIDE_MYSECTIONS => GD_MENU_SIDENAV_MYSECTIONS,
            self::COMPONENT_DATALOAD_MENU_BODY_ADDCONTENT => GD_MENU_TOPNAV_ADDCONTENT,
            self::COMPONENT_DATALOAD_MENU_BODY_SECTIONS => GD_MENU_SIDENAV_SECTIONS,
            self::COMPONENT_DATALOAD_MENU_BODY_MYSECTIONS => GD_MENU_SIDENAV_MYSECTIONS,
            self::COMPONENT_DATALOAD_MENU_BODY_ABOUT => GD_MENU_TOPNAV_ABOUT,
            default => parent::getMenu($component),
        };
    }
}



