<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * CDN Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_CoreProcessors_CDN_Hooks {

	function __construct() {

		add_filter(
			'PoP_CDNCore_Job_ThumbprintsConfig:criteriaitems:thumbprint:hasParamValues',
			array($this, 'get_thumbprint_paramvalues'),
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

	// Comment Leo 31/01/2017: No need to define the thumbprints below, since the author URL (eg: getpop.org/en/u/leo/)
	// has already been set to use thumbprint POST
	// function get_thumbprint_paramvalues($paramvalues, $thumbprint) {
		
	// 	if ($thumbprint == POP_CDNCORE_THUMBPRINT_USER) {

	// 		$pages = array(
	// 			POP_COREPROCESSORS_PAGE_DESCRIPTION,
	// 			POP_COREPROCESSORS_PAGE_MAIN,
	// 			POP_COREPROCESSORS_PAGE_SUMMARY,
	// 			POP_COREPROCESSORS_PAGE_RELATEDCONTENT,
	// 			POP_COREPROCESSORS_PAGE_POSTAUTHORS,
	// 			POP_COREPROCESSORS_PAGE_RECOMMENDEDBY,
	// 			POP_COREPROCESSORS_PAGE_FOLLOWERS,
	// 			POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS,
	// 			POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS,
	// 			POP_COREPROCESSORS_PAGE_UPVOTEDBY,
	// 			POP_COREPROCESSORS_PAGE_DOWNVOTEDBY,
	// 			POP_COREPROCESSORS_PAGE_SUBSCRIBERS,
	// 			POP_COREPROCESSORS_PAGE_SUBSCRIBEDTO,
	// 		);
	// 		foreach ($pages as $page) {				

	// 			// Array of: elem[0] = URL param, elem[1] = value
	// 			$paramvalues[] = array(
	// 				GD_URLPARAM_TAB, 
	// 				trailingslashit(GD_TemplateManager_Utils::get_page_path($page))
	// 			);
	// 		}
	// 	}
	// 	elseif ($thumbprint == POP_CDNCORE_THUMBPRINT_POST) {

	// 		$pages = array(
	// 			POP_COREPROCESSORS_PAGE_MAIN,
	// 			POP_COREPROCESSORS_PAGE_SUMMARY,
	// 			POP_COREPROCESSORS_PAGE_RELATEDCONTENT,
	// 			POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS,
	// 		);
	// 		foreach ($pages as $page) {				

	// 			$paramvalues[] = array(
	// 				GD_URLPARAM_TAB, 
	// 				trailingslashit(GD_TemplateManager_Utils::get_page_path($page))
	// 			);
	// 		}
	// 	}
		
	// 	return $paramvalues;
	// }

	function get_thumbprint_paramvalues($paramvalues, $thumbprint) {
		
		$pages = array();
		if ($thumbprint == POP_CDNCORE_THUMBPRINT_COMMENT) {

			// Fetch the comments through lazy-load when calling POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS
			// eg: https://www.mesym.com/en/loaders/posts/layouts/?pid[0]=21537&layouts[0]=highrefby-fullview&layouts[1]=refby-fullview&layouts[2]=postcomments&format=requestlayouts&target=main&module=settingsdata&output=json&theme=wassup&thememode=sliding&themestyle=swift
			$paramvalues[] = array(
				GD_URLPARAM_LAYOUTS, 
				GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT
			);
		}
		
		return $paramvalues;
	}

	function get_thumbprint_noparamvalues($noparamvalues, $thumbprint) {
		
		// Please notice: 
		// getpop.org/en/u/leo/ has thumbprints POST + USER, but
		// getpop.org/en/u/leo/?tab=description needs only thumbprint USER
		// for that, we have added criteria "noParamValues", checking that it is thumbprint POST
		// as long as the specified tabs (description, followers, etc) are not in the URL
		// This must be added on those hooks.php files implementing the corresponding pages
		// (eg: pop-coreprocessors handles tab=description, etc)
		if ($thumbprint == POP_CDNCORE_THUMBPRINT_POST) {

			$pages = array_filter(array(
				POP_COREPROCESSORS_PAGE_DESCRIPTION,
				POP_COREPROCESSORS_PAGE_POSTAUTHORS,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDBY,
				POP_COREPROCESSORS_PAGE_FOLLOWERS,
				POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS,
				POP_COREPROCESSORS_PAGE_UPVOTEDBY,
				POP_COREPROCESSORS_PAGE_DOWNVOTEDBY,
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
new PoP_CoreProcessors_CDN_Hooks();
