<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * CDN Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPThemeWassup_EM_CDN_Hooks {

	function __construct() {

		add_filter(
			'PoP_CDNCore_Job_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
			array($this, 'get_thumbprint_partialpaths'),
			10,
			2
		);
	}

	function get_thumbprint_partialpaths($paths, $thumbprint) {
		
		$pages = array();
		if ($thumbprint == POP_EM_CDN_THUMBPRINT_LOCATION) {

			$pages = array(
				POP_EM_POPPROCESSORS_PAGE_LOCATIONSMAP,
			);
		}
		elseif (in_array($thumbprint, array(
			POP_CDNCORE_THUMBPRINT_POST, 
			POP_CDNCORE_THUMBPRINT_USER
		))) {

			$pages = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS,
				POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS,
			);
		}
		foreach ($pages as $page) {

			$paths[] = trailingslashit(GD_TemplateManager_Utils::get_page_path($page));
		}

		// $pages = array();
		// if ($thumbprint == POP_CDNCORE_THUMBPRINT_POST) {

		// 	$pages = array_filter(array(
		// 		POP_EM_POPPROCESSORS_PAGE_LOCATIONSMAP,
		// 		POPTHEME_WASSUP_EM_PAGE_EVENTS,
		// 		POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR,
		// 		POPTHEME_WASSUP_EM_PAGE_PASTEVENTS,
		// 	));
		// }
		// elseif ($thumbprint == POP_CDNCORE_THUMBPRINT_USER) {

		// 	$pages = array_filter(array(
		// 		POPTHEME_WASSUP_EM_PAGE_EVENTS,
		// 		POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR,
		// 		POPTHEME_WASSUP_EM_PAGE_PASTEVENTS,
		// 	));
		// }
		// foreach ($pages as $page) {

		// 	$paths[] = trailingslashit(GD_TemplateManager_Utils::get_page_path($page));
		// }
		
		return $paths;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_EM_CDN_Hooks();

