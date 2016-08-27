<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_PageSectionSettingsProcessor_Manager {

	var $processors;

	function __construct() {

		$this->processors = array();
	}
	
	function add($processor) {

		$this->processors[] = $processor;
	}

	function add_sideinfo_single_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_sideinfo_single_blockunits($ret, $template_id);
		}
	}

	function add_sideinfo_author_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_sideinfo_author_blockunits($ret, $template_id);
		}
	}

	function add_sideinfo_tag_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_sideinfo_tag_blockunits($ret, $template_id);
		}
	}

	function add_sideinfo_page_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_sideinfo_page_blockunits($ret, $template_id);
		}
	}

	function add_sideinfo_home_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_sideinfo_home_blockunits($ret, $template_id);
		}
	}

	function add_sideinfo_empty_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_sideinfo_empty_blockunits($ret, $template_id);
		}
	}

	function add_single_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_single_blockunits($ret, $template_id);
		}
	}

	function add_author_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_author_blockunits($ret, $template_id);
		}
	}

	function add_home_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_home_blockunits($ret, $template_id);
		}
	}

	function add_tag_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_tag_blockunits($ret, $template_id);
		}
	}

	function add_error_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_error_blockunits($ret, $template_id);
		}
	}

	function add_page_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_page_blockunits($ret, $template_id);
		}
	}

	function add_pagetab_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_pagetab_blockunits($ret, $template_id);
		}
	}

	function add_hometab_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_hometab_blockunits($ret, $template_id);
		}
	}

	function add_tagtab_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_tagtab_blockunits($ret, $template_id);
		}
	}

	function add_singletab_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_singletab_blockunits($ret, $template_id);
		}
	}

	function add_authortab_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_authortab_blockunits($ret, $template_id);
		}
	}

	function add_errortab_blockunits(&$ret, $template_id) {

		foreach ($this->processors as $processor) {
			$processor->add_errortab_blockunits($ret, $template_id);
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $wassup_pagesectionsettingsprocessor_manager;
$wassup_pagesectionsettingsprocessor_manager = new Wassup_PageSectionSettingsProcessor_Manager();
