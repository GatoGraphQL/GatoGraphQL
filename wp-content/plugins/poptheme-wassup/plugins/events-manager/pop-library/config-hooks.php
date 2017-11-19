<?php

class PoPTheme_EM_Processors_ConfigHooks {

	function __construct() {

		add_filter('keep_database_in_output', array($this, 'keep_database_in_output'));
	}

	function keep_database_in_output($keep) {

		// The Events Calendar cannot remove the DB, since it requires the DB data to add events to the calendar all on runtime
		// (until we can produce the HTML for the calendar also on the server-side. Check: https://github.com/leoloso/PoP/issues/59)
		if ($this->is_calendar()) {

			return true;
		}

		return $keep;
	}

	protected function is_calendar() {

		// Use this function to check if it's calendar for either 'page', 'tag' or 'author' hierarchies
		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
		return $page_id == POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_EM_Processors_ConfigHooks();