<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * ThemeStyle Swift
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_THEMESTYLE_WASSUP_SWIFT', 'swift');

class GD_ThemeMode_Wassup_Swift extends GD_WassupThemeStyle_Base {

	function __construct() {
		
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype'), 10, 2);
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_TABLE.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype'), 10, 2);
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDAR.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype'), 10, 2);
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_MAP.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype'), 10, 2);
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDARMAP.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype'), 10, 2);

		parent::__construct();
	}

	function get_name() {
		
		return GD_THEMESTYLE_WASSUP_SWIFT;
	}

	function get_settingsprocessors_blocktype($type) {

		return POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_ThemeMode_Wassup_Swift();
