<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * ThemeMode/Style Hooks
 * ---------------------------------------------------------------------------------------------------------------*/
define ('POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK', 'block');
define ('POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCKGROUP', 'blockgroup');

define ('POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED', 'settingsprocessors-blocktype-feed');
define ('POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_TABLE', 'settingsprocessors-blocktype-table');
define ('POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CAROUSEL', 'settingsprocessors-blocktype-carousel');
define ('POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDAR', 'settingsprocessors-blocktype-calendar');
define ('POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_MAP', 'settingsprocessors-blocktype-map');
define ('POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDARMAP', 'settingsprocessors-blocktype-calendarmap');

class Wassup_PageSectionSettingsProcessorBase {

	function __construct() {

		add_action('init', array($this, 'init'));
	}

	function init() {

		global $wassup_pagesectionsettingsprocessor_manager;
		$wassup_pagesectionsettingsprocessor_manager->add($this);
	}

	function add_sideinfo_single_blockunits(&$ret, $template_id) {
	}

	function add_sideinfo_author_blockunits(&$ret, $template_id) {
	}

	function add_sideinfo_tag_blockunits(&$ret, $template_id) {
	}

	function add_sideinfo_page_blockunits(&$ret, $template_id) {
	}

	function add_sideinfo_home_blockunits(&$ret, $template_id) {
	}

	function add_sideinfo_empty_blockunits(&$ret, $template_id) {
	}

	function add_single_blockunits(&$ret, $template_id) {
	}

	function add_author_blockunits(&$ret, $template_id) {
	}

	function add_home_blockunits(&$ret, $template_id) {
	}

	function add_tag_blockunits(&$ret, $template_id) {
	}

	function add_error_blockunits(&$ret, $template_id) {
	}

	function add_page_blockunits(&$ret, $template_id) {
	}

	function add_pagetab_blockunits(&$ret, $template_id) {
	}

	function add_hometab_blockunits(&$ret, $template_id) {
	}

	function add_tagtab_blockunits(&$ret, $template_id) {
	}

	function add_singletab_blockunits(&$ret, $template_id) {
	}

	function add_authortab_blockunits(&$ret, $template_id) {
	}

	function add_errortab_blockunits(&$ret, $template_id) {
	}
}