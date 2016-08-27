<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * ThemeStyle Consistent
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_THEMESTYLE_WASSUP_EXPANSIVE', 'expansive');

class GD_ThemeMode_Wassup_Expansive extends GD_WassupThemeStyle_Base {

	function __construct() {
		
		// Hooks to allow the themestyles to do some functionality
		add_filter(POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_pagesectionside_logosize'));
		add_filter(POP_HOOK_BLOCKGROUP_TOPWIDGETS_INCOLUMNS.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_blockgroup_topwidgets_incolumns'));
		add_filter(POP_HOOK_CAROUSEL_USERS_GRIDCLASS.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_carousel_users_gridclass'));
		add_filter(POP_HOOK_SCROLLINNER_THUMBNAIL_GRID.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_scrollinner_thumbnail_grid'));
		
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_TABLE.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDAR.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_MAP.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDARMAP.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype'));

		parent::__construct();
	}

	function get_name() {
		
		return GD_THEMESTYLE_WASSUP_EXPANSIVE;
	}

	function get_settingsprocessors_blocktype($type) {

		// ThemeStyle is called "Expansive" because it allows the feed to be presented in different views (detail, list, map, etc)
		return POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCKGROUP;
	}

	function get_scrollinner_thumbnail_grid($grid) {

		return array(
			'row-items' => 3, 
			'class' => 'col-xsm-4'
		);
	}

	function get_carousel_users_gridclass($class) {

		return 'col-xs-4 col-sm-6 col-md-4 no-padding';
	}

	function get_blockgroup_topwidgets_incolumns($incolumns) {

		return true;
	}

	function get_pagesectionside_logosize($size) {

		return 'large-inverse';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_ThemeMode_Wassup_Expansive();
