<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * ThemeMode Embed
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_THEMEMODE_WASSUP_SIMPLE', 'simple');

class GD_ThemeMode_Wassup_Simple extends GD_WassupThemeMode_Base {

	function __construct() {
		
		// Hooks to allow the thememodes to do some functionality
		add_filter(POP_HOOK_POPFRONTEND_BACKGROUNDLOAD.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'background_load'));
		add_filter(POP_HOOK_TOPLEVEL_FRAMEPAGESECTIONS.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'get_framepagesections'), 10, 2);
		// add_filter(POP_HOOK_SW_APPSHELL_REOPENTABS.':'.$this->get_theme()->get_name().':'.$this->get_name(), '__return_false');
		// add_filter(POP_HOOK_PAGETABS_ADDOPENTAB.':'.$this->get_theme()->get_name().':'.$this->get_name(), '__return_false');
		
		// add_filter(POP_HOOK_POPFRONTEND_KEEPOPENTABS.':'.$this->get_theme()->get_name().':'.$this->get_name(), '__return_false');

		// add_filter('PoP_ServiceWorkers_Job_CacheResources:precache', array($this, 'get_precache_list'), 10, 2);
		
		parent::__construct();
	}

	function get_name() {
		
		return GD_THEMEMODE_WASSUP_SIMPLE;
	}

	protected function get_loaders_initialframes() {

		return array(
			get_permalink(POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES) => array(
				GD_URLPARAM_TARGET_MAIN,
				GD_URLPARAM_TARGET_ADDONS,
				GD_URLPARAM_TARGET_MODALS,
			)
		);
	}

	// function get_precache_list($precache, $resourceType) {

	// 	if ($resourceType == 'json') {

	// 		// All the pages in the background are also precached using Service Workers
	// 		$urls = array();
	// 		foreach ($this->get_loaders_initialframes() as $page => $targets) {
	// 			foreach ($targets as $target) {

	// 				// Important: do not change the order in which these attributes are added, or it can ruin other things,
	// 				// eg: this is the same order in which args are added to the URL in function fetchPageSection in pop-manager.js
	// 				$url = get_permalink($page);
	// 				$url = add_query_arg(GD_URLPARAM_TARGET, $target, $url);
	// 				$url = add_query_arg(GD_URLPARAM_MODULE, GD_URLPARAM_MODULE_SETTINGSDATA, $url);
	// 				$url = add_query_arg(GD_URLPARAM_OUTPUT, GD_URLPARAM_OUTPUT_JSON, $url);
	// 				$urls[] = $url;
	// 			}
	// 		}

	// 		if ($urls) {

	// 			// Comment Leo: not needed, since qTransX is adding the language for all URLs at the end, for all URLs with language information
	// 			// // Allow qTranslate to add all the same items for all other languages to this list
	// 			// $urls = apply_filters(
	// 			// 	'GD_ThemeMode_Wassup_Simple:precache:initialframes',
	// 			// 	$urls
	// 			// );
	// 			return array_merge(
	// 				$precache,
	// 				$urls
	// 			);
	// 		}
	// 	}

	// 	return $precache;
	// }

	function background_load($pages) {

		return array_merge(
			$pages,
			$this->get_loaders_initialframes()
		);
	}
	function get_framepagesections($pagesections, $template_id) {

		$pagesections = array_merge(
			$pagesections,
			array(
				GD_TEMPLATE_PAGESECTION_TOPSIMPLE,
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
new GD_ThemeMode_Wassup_Simple();
