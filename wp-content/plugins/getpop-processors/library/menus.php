<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Menus
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define('GD_MENU_SIDEBAR_DOCUMENTATION', 'menu-sidebar-documentation');
define('GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONARCHITECTURE', 'menu-sidebar-documentation-sectionarchitecture');
define('GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONCONTROLLER', 'menu-sidebar-documentation-sectioncontroller');
define('GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONMODEL', 'menu-sidebar-documentation-sectionmodel');
define('GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONVIEW', 'menu-sidebar-documentation-sectionview');
define('GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONAPPLICATIONLOGIC', 'menu-sidebar-documentation-sectionapplicationlogic');

// Register the Menus
register_nav_menus(array(
	GD_MENU_SIDEBAR_DOCUMENTATION => 'GetPoP: Documentation',
	GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONARCHITECTURE => 'GetPoP: Documentation: Section Architecture',
	GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONCONTROLLER => 'GetPoP: Documentation: Section Controller',
	GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONMODEL => 'GetPoP: Documentation: Section Model',
	GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONVIEW => 'GetPoP: Documentation: Section View',
	GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONAPPLICATIONLOGIC => 'GetPoP: Documentation: Section Application Logic',
));