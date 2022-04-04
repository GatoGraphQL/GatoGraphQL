<?php

class PoP_Module_Processor_CustomMenuDataloads extends PoP_Module_Processor_MenuDataloadsBase
{
    public final const MODULE_DATALOAD_MENU_SIDEBAR_ABOUT = 'dataload-menu-sidebar-about';
    public final const MODULE_DATALOAD_MENU_TOPNAV_USERLOGGEDIN = 'dataload-menu-top-userloggedin';
    public final const MODULE_DATALOAD_MENU_TOPNAV_USERNOTLOGGEDIN = 'dataload-menu-top-usernotloggedin';
    public final const MODULE_DATALOAD_MENU_TOPNAV_ABOUT = 'dataload-menu-top-about';
    public final const MODULE_DATALOAD_MENU_TOP_ADDNEW = 'dataload-menu-top-addnew';
    public final const MODULE_DATALOAD_MENU_HOME_USERNOTLOGGEDIN = 'dataload-menu-home-usernotloggedin';
    public final const MODULE_DATALOAD_MENU_SIDE_ADDNEW = 'dataload-menu-side-addnew';
    public final const MODULE_DATALOAD_MENU_SIDE_SECTIONS = 'dataload-menu-side-sections';
    public final const MODULE_DATALOAD_MENU_SIDE_SECTIONS_MULTITARGET = 'dataload-menu-side-sections-multitarget';
    public final const MODULE_DATALOAD_MENU_SIDE_MYSECTIONS = 'dataload-menu-side-mysections';
    public final const MODULE_DATALOAD_MENU_BODY_ADDCONTENT = 'dataload-menu-body-addcontent';
    public final const MODULE_DATALOAD_MENU_BODY_SECTIONS = 'dataload-menu-body-sections';
    public final const MODULE_DATALOAD_MENU_BODY_MYSECTIONS = 'dataload-menu-body-mysections';
    public final const MODULE_DATALOAD_MENU_BODY_ABOUT = 'dataload-menu-body-about';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_MENU_SIDEBAR_ABOUT],
            [self::class, self::MODULE_DATALOAD_MENU_TOPNAV_USERLOGGEDIN],
            [self::class, self::MODULE_DATALOAD_MENU_TOPNAV_USERNOTLOGGEDIN],
            [self::class, self::MODULE_DATALOAD_MENU_TOPNAV_ABOUT],
            [self::class, self::MODULE_DATALOAD_MENU_TOP_ADDNEW],
            [self::class, self::MODULE_DATALOAD_MENU_HOME_USERNOTLOGGEDIN],
            [self::class, self::MODULE_DATALOAD_MENU_SIDE_ADDNEW],
            [self::class, self::MODULE_DATALOAD_MENU_SIDE_SECTIONS],
            [self::class, self::MODULE_DATALOAD_MENU_SIDE_SECTIONS_MULTITARGET],
            [self::class, self::MODULE_DATALOAD_MENU_SIDE_MYSECTIONS],
            [self::class, self::MODULE_DATALOAD_MENU_BODY_ADDCONTENT],
            [self::class, self::MODULE_DATALOAD_MENU_BODY_SECTIONS],
            [self::class, self::MODULE_DATALOAD_MENU_BODY_MYSECTIONS],
            [self::class, self::MODULE_DATALOAD_MENU_BODY_ABOUT],
        );
    }

    // function getRelevantRoute(array $module, array &$props) {

    //     $routes = array(
    //         self::MODULE_DATALOAD_MENU_BODY_ABOUT => POP_COMMONPAGES_ROUTE_ABOUT,
    //         self::MODULE_DATALOAD_MENU_BODY_ADDCONTENT => POP_CONTENTCREATION_ROUTE_ADDCONTENT,
    //     );
    //     return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    // }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_DATALOAD_MENU_SIDEBAR_ABOUT => [PoP_Module_Processor_CustomMenuSidebars::class, PoP_Module_Processor_CustomMenuSidebars::MODULE_SIDEBAR_MENU_ABOUT],
            self::MODULE_DATALOAD_MENU_TOPNAV_USERLOGGEDIN => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::MODULE_INDENTMENU],
            self::MODULE_DATALOAD_MENU_TOPNAV_USERNOTLOGGEDIN => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::MODULE_INDENTMENU],
            self::MODULE_DATALOAD_MENU_TOPNAV_ABOUT => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::MODULE_INDENTMENU],
            self::MODULE_DATALOAD_MENU_TOP_ADDNEW => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::MODULE_INDENTMENU],
            self::MODULE_DATALOAD_MENU_HOME_USERNOTLOGGEDIN => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::MODULE_INDENTMENU],
            self::MODULE_DATALOAD_MENU_SIDE_ADDNEW => [PoP_Module_Processor_Menus::class, PoP_Module_Processor_Menus::MODULE_DROPDOWNBUTTONMENU_SIDE],
            self::MODULE_DATALOAD_MENU_SIDE_SECTIONS => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::MODULE_INDENTMENU],
            self::MODULE_DATALOAD_MENU_SIDE_SECTIONS_MULTITARGET => [PoP_Module_Processor_Menus::class, PoP_Module_Processor_Menus::MODULE_MULTITARGETINDENTMENU],
            self::MODULE_DATALOAD_MENU_SIDE_MYSECTIONS => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::MODULE_INDENTMENU],
            self::MODULE_DATALOAD_MENU_BODY_ADDCONTENT => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::MODULE_INDENTMENU],
            self::MODULE_DATALOAD_MENU_BODY_SECTIONS => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::MODULE_INDENTMENU],
            self::MODULE_DATALOAD_MENU_BODY_MYSECTIONS => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::MODULE_INDENTMENU],
            self::MODULE_DATALOAD_MENU_BODY_ABOUT => [PoP_Module_Processor_IndentMenus::class, PoP_Module_Processor_IndentMenus::MODULE_INDENTMENU],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getMenu(array $module)
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_MENU_SIDEBAR_ABOUT => GD_MENU_SIDEBAR_ABOUT,
            self::MODULE_DATALOAD_MENU_TOPNAV_USERLOGGEDIN => GD_MENU_TOPNAV_USERLOGGEDIN,
            self::MODULE_DATALOAD_MENU_TOPNAV_USERNOTLOGGEDIN => GD_MENU_TOPNAV_USERNOTLOGGEDIN,
            self::MODULE_DATALOAD_MENU_TOPNAV_ABOUT => GD_MENU_TOPNAV_ABOUT,
            self::MODULE_DATALOAD_MENU_TOP_ADDNEW => GD_MENU_TOPNAV_ADDCONTENT,
            self::MODULE_DATALOAD_MENU_HOME_USERNOTLOGGEDIN => GD_MENU_TOPNAV_USERNOTLOGGEDIN,
            self::MODULE_DATALOAD_MENU_SIDE_ADDNEW => GD_MENU_TOPNAV_ADDCONTENT,
            self::MODULE_DATALOAD_MENU_SIDE_SECTIONS => GD_MENU_SIDENAV_SECTIONS,
            self::MODULE_DATALOAD_MENU_SIDE_SECTIONS_MULTITARGET => GD_MENU_SIDENAV_SECTIONS,
            self::MODULE_DATALOAD_MENU_SIDE_MYSECTIONS => GD_MENU_SIDENAV_MYSECTIONS,
            self::MODULE_DATALOAD_MENU_BODY_ADDCONTENT => GD_MENU_TOPNAV_ADDCONTENT,
            self::MODULE_DATALOAD_MENU_BODY_SECTIONS => GD_MENU_SIDENAV_SECTIONS,
            self::MODULE_DATALOAD_MENU_BODY_MYSECTIONS => GD_MENU_SIDENAV_MYSECTIONS,
            self::MODULE_DATALOAD_MENU_BODY_ABOUT => GD_MENU_TOPNAV_ABOUT,
            default => parent::getMenu($module),
        };
    }
}



