<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * CDN Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_EM_CDN_Hooks {

	function __construct() {

		add_filter(
			'PoP_CDNCore_Job_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
			array($this, 'get_thumbprint_partialpaths'),
			10,
			2
		);
	}

	function get_thumbprint_partialpaths($paths, $thumbprint) {
		
		if ($thumbprint == POP_EM_CDN_THUMBPRINT_LOCATION) {
		// if ($thumbprint == POP_CDNCORE_THUMBPRINT_POST) {

			$pages = array_filter(array(
				POP_EM_POPPROCESSORS_PAGE_LOCATIONS,
			));
			foreach ($pages as $page) {

				$paths[] = trailingslashit(GD_TemplateManager_Utils::get_page_path($page));
			}
		}
		
		return $paths;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_EM_CDN_Hooks();
