<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * CDN Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_URE_CDN_Hooks {

	function __construct() {

		add_filter(
			'PoP_CDNCore_Job_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
			array($this, 'get_thumbprint_partialpaths'),
			10,
			2
		);
		add_filter(
			'PoP_CDNCore_Job_ThumbprintsConfig:criteriaitems:thumbprint:noParamValues',
			array($this, 'get_thumbprint_noparamvalues'),
			10,
			2
		);
	}

	function get_thumbprint_partialpaths($paths, $thumbprint) {
		
		if ($thumbprint == POP_CDNCORE_THUMBPRINT_USER) {

			$pages = array_filter(array(
				POP_URE_POPPROCESSORS_PAGE_COMMUNITIES,
				POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS,
				POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS,
			));
			foreach ($pages as $page) {

				$paths[] = trailingslashit(GD_TemplateManager_Utils::get_page_path($page));
			}
		}
		
		return $paths;
	}

	function get_thumbprint_noparamvalues($noparamvalues, $thumbprint) {
		
		// Please notice: 
		// getpop.org/en/u/pop/ has thumbprints POST + USER, but
		// getpop.org/en/u/pop/?tab=members needs only thumbprint USER
		if ($thumbprint == POP_CDNCORE_THUMBPRINT_POST) {

			$pages = array_filter(array(
				POP_URE_POPPROCESSORS_PAGE_MEMBERS,
			));

			// Add the values to the configuration
			foreach ($pages as $page) {

				// Array of: elem[0] = URL param, elem[1] = value
				$noparamvalues[] = array(
					GD_URLPARAM_TAB, 
					GD_TemplateManager_Utils::get_page_path($page)
				);
			}
		}
		
		return $noparamvalues;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_URE_CDN_Hooks();

