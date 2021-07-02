<?php

define('GD_MENU_TOPNAV_USERLOGGEDIN', 'menu-topnav-userloggedin');
define('GD_MENU_TOPNAV_USERNOTLOGGEDIN', 'menu-topnav-usernotloggedin');
define('GD_MENU_TOPNAV_ADDCONTENT', 'menu-topnav-addcontent');
define('GD_MENU_TOPNAV_ABOUT', 'menu-topnav-about');
define('GD_MENU_SIDENAV_SECTIONS', 'menu-sidenav-sections');
define('GD_MENU_SIDENAV_MYSECTIONS', 'menu-sidenav-mysections');
define('GD_MENU_SIDEBAR_ABOUT', 'menu-sidebar-about');


// Register the Menus
register_nav_menus(
    array(
        GD_MENU_SIDEBAR_ABOUT => 'Sidebar About',
        GD_MENU_TOPNAV_USERLOGGEDIN => 'Theme Sliding: Top Navigation: User Logged in Menu',
        GD_MENU_TOPNAV_USERNOTLOGGEDIN => 'Theme Sliding: Top Navigation: User Not Logged in Menu',
        GD_MENU_TOPNAV_ADDCONTENT => 'Theme Sliding: Top Navigation: Add Content',
        GD_MENU_TOPNAV_ABOUT => 'Theme Sliding: Top Navigation: About',
        GD_MENU_SIDENAV_SECTIONS => 'Theme Sliding: Side Navigation: Sections',
        GD_MENU_SIDENAV_MYSECTIONS => 'Theme Sliding: Side Navigation: My Sections',
    )
);
