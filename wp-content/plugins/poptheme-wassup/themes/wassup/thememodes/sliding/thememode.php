<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Themes
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_THEMEMODE_WASSUP_SLIDING', 'sliding');

class GD_ThemeMode_Wassup_Sliding extends GD_WassupThemeMode_Base {

	function __construct() {
		
		// Hooks to allow the thememodes to do some functionality
		add_filter(POP_HOOK_POPFRONTEND_BACKGROUNDLOAD.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'background_load'));
		add_filter(POP_HOOK_TOPLEVEL_FRAMEPAGESECTIONS.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_framepagesections'), 10, 2);

		parent::__construct();
	}

	function get_name() {
		
		return GD_THEMEMODE_WASSUP_SLIDING;
	}

	function background_load($pages) {

		$pages[POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES] = array(
			GD_URLPARAM_TARGET_MAIN,
			GD_URLPARAM_TARGET_ADDONS,
			GD_URLPARAM_TARGET_MODALS,
		);
		return $pages;
	}

	function get_framepagesections($pagesections, $template_id) {

		$pagesections = array_merge(
			$pagesections,
			array(
				GD_TEMPLATE_PAGESECTION_TOP,
				GD_TEMPLATE_PAGESECTION_SIDE,
				GD_TEMPLATE_PAGESECTION_BACKGROUND,
				GD_TEMPLATE_PAGESECTION_HOVER,
				GD_TEMPLATE_PAGESECTION_NAVIGATOR,
			)
		);

		switch ($template_id) {

			case GD_TEMPLATE_TOPLEVEL_HOME:
			
				// $pagesections[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME;//GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_ADDONS_HOME;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_MODALS_HOME;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWHOME;//GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY;
				break;

			case GD_TEMPLATE_TOPLEVEL_TAG:
			
				// $pagesections[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG;//GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_ADDONS_TAG;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_MODALS_TAG;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG;//GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY;
				break;

			case GD_TEMPLATE_TOPLEVEL_PAGE:

				// $pagesections[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_ADDONS_PAGE;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_MODALS_PAGE;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE;
				break;

			case GD_TEMPLATE_TOPLEVEL_SINGLE:

				// $pagesections[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_MODALS_SINGLE;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWSINGLE;
				break;

			case GD_TEMPLATE_TOPLEVEL_AUTHOR:
				
				// $pagesections[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWAUTHOR;
				break;

			case GD_TEMPLATE_TOPLEVEL_404:

				// $pagesections[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_ADDONS_404;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_ADDONTABS_404;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_MODALS_404;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_QUICKVIEW404;
				$pagesections[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY;
				break;
		}

		return $pagesections;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_ThemeMode_Wassup_Sliding();
