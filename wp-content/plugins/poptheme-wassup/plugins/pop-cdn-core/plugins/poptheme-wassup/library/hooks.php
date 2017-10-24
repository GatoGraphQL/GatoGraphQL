<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * CDN Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPThemeWassup_CDN_Hooks {

	function __construct() {

		add_filter(
            'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            array($this, 'get_thumbprint_partialpaths'),
            10,
            2
        );
		add_filter(
			'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:hasParamValues',
			array($this, 'get_thumbprint_paramvalues'),
			10,
			2
		);
	}

	function get_thumbprint_partialpaths($paths, $thumbprint) {
		
		$pages = array();
		if ($thumbprint == POP_CDNCORE_THUMBPRINT_PAGE) {

			$pages = array_filter(array(
				POPTHEME_WASSUP_PAGE_ACCOUNTFAQ,
				POPTHEME_WASSUP_PAGE_ADDCONTENTFAQ,
			));
		}
		elseif ($thumbprint == POP_CDNCORE_THUMBPRINT_POST) {

			$pages = array_filter(array(
				POPTHEME_WASSUP_PAGE_HIGHLIGHTS,
				POPTHEME_WASSUP_PAGE_WEBPOSTS,
			));
		}

		// Add the values to the configuration
		foreach ($pages as $page) {				

			$paths[] = trailingslashit(GD_TemplateManager_Utils::get_page_path($page));
		}
		
		return $paths;
	}

	function get_thumbprint_paramvalues($paramvalues, $thumbprint) {
		
		$pages = array();
		if ($thumbprint == POP_CDNCORE_THUMBPRINT_COMMENT) {

			// Fetch the comments through lazy-load when calling POP_WPAPI_PAGE_LOADERS_POSTS_FIELDS, such as Articles in the feed view
			// eg: https://www.mesym.com/en/loaders/posts/data/?pid[0]=21566&pid[1]=21542&pid[2]=21537&pid[3]=21523&pid[4]=21521&pid[5]=21472&fields[0]=recommendpost-count&fields[1]=recommendpost-count-plus1&fields[2]=userpostactivity-count&format=updatedata&target=main&module=settingsdata&output=json&theme=wassup&thememode=sliding&themestyle=swift
			$paramvalues[] = array(
				GD_URLPARAM_FIELDS, 
				'userpostactivity-count'
			);
		}
		
		return $paramvalues;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_CDN_Hooks();
