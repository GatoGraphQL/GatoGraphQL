<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * ThemeMode Print
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_THEMEMODE_WASSUP_PRINT', 'print');

class GD_ThemeMode_Wassup_Print extends GD_WassupThemeMode_Base {

	function __construct() {
		
		add_filter('gd_jquery_constants', array($this, 'jquery_constants'));

		// Hooks to allow the thememodes to do some functionality
		add_filter(POP_HOOK_TOPLEVEL_FRAMEPAGESECTIONS.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_framepagesections'), 10, 2);
		add_filter(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_pagesection_jsmethod'), 10, 2);
		add_filter(POP_HOOK_BLOCKSBASE_FILTERINGBYSHOWFILTER.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'filteringby_showfilter'));
		add_filter(POP_HOOK_BLOCKSIDEBARS_ORIENTATION.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_sidebar_orientation'));
		add_filter(POP_HOOK_CUSTOMTOPLEVELS_ADDPAGETABS.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'add_pagetabs'));

		parent::__construct();
	}

	function jquery_constants($jquery_constants) {

		$jquery_constants['THEMEMODE_WASSUP_PRINT'] = GD_THEMEMODE_WASSUP_PRINT;
		return $jquery_constants;
	}

	function get_name() {
		
		return GD_THEMEMODE_WASSUP_PRINT;
	}

	function get_framepagesections($pagesections, $template_id) {

		$pagesections[] = GD_TEMPLATE_PAGESECTION_HOVER;

		return $pagesections;
	}

	function get_pagesection_jsmethod($jsmethod, $template_id) {

		// Remove all the scrollbars
		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_HOME:
			case GD_TEMPLATE_PAGESECTION_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGE:
			case GD_TEMPLATE_PAGESECTION_SINGLE:
			case GD_TEMPLATE_PAGESECTION_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_404:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR:

				$this->remove_jsmethod($jsmethod, 'scrollbarVertical');
				break;
		}

		// Add the automatic print
		switch ($template_id) {
			
			case GD_TEMPLATE_PAGESECTION_HOME:
			case GD_TEMPLATE_PAGESECTION_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGE:
			case GD_TEMPLATE_PAGESECTION_SINGLE:
			case GD_TEMPLATE_PAGESECTION_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_404:

				$this->add_jsmethod($jsmethod, 'printWindow');
				break;
		}

		return $jsmethod;
	}

	function filteringby_showfilter($showfilter) {

		return false;
	}

	function get_sidebar_orientation($orientation) {

		return 'horizontal';
	}

	function add_pagetabs($add) {

		return false;
	}
	// function get_framepagesections($pagesections) {

	// 	return array(
	// 		GD_TEMPLATEID_PAGESECTIONSETTINGSID_MAIN,
	// 		GD_TEMPLATEID_PAGESECTIONSETTINGSID_SIDEINFO,
	// 		GD_TEMPLATEID_PAGESECTIONSETTINGSID_PAGETABS,
	// 	);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_ThemeMode_Wassup_Print();
