<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * CDN Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_WPAPI_CDN_Hooks {

	function __construct() {

		add_filter(
            'PoP_CDNCore_Job_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            array($this, 'get_thumbprint_partialpaths'),
            10,
            2
        );
		add_filter(
			'PoP_CDNCore_Job_ThumbprintsConfig:criteriaitems:thumbprint:hasParamValues',
			array($this, 'get_thumbprint_paramvalues'),
			10,
			2
		);
	}

	function get_thumbprint_partialpaths($paths, $thumbprint) {

		// Trending Tags: added also dependency on POST and COMMENT (apart from TAG),
		// because a trending tag may not be newly created to become trending, so TAG alone doesn't work
		
		$pages = array();
		if ($thumbprint == POP_CDNCORE_THUMBPRINT_USER) {

			$pages = array_filter(array(
				POP_WPAPI_PAGE_SEARCHPOSTS,
				POP_WPAPI_PAGE_SEARCHUSERS,
				POP_WPAPI_PAGE_ALLCONTENT,
				POP_WPAPI_PAGE_ALLUSERS,
				POP_WPAPI_PAGE_COMMENTS,
				POP_WPAPI_PAGE_LOADERS_POSTS_FIELDS,
				POP_WPAPI_PAGE_LOADERS_USERS_FIELDS,
				POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS,
				POP_WPAPI_PAGE_LOADERS_USERS_LAYOUTS,
			));
		}
		elseif ($thumbprint == POP_CDNCORE_THUMBPRINT_POST) {

			$pages = array_filter(array(
				POP_WPAPI_PAGE_SEARCHPOSTS,
				POP_WPAPI_PAGE_ALLCONTENT,
				POP_WPAPI_PAGE_TRENDINGTAGS,
				POP_WPAPI_PAGE_LOADERS_POSTS_FIELDS,
				POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS,
			));
		}
		elseif ($thumbprint == POP_CDNCORE_THUMBPRINT_COMMENT) {

			$pages = array_filter(array(
				POP_WPAPI_PAGE_COMMENTS,
				POP_WPAPI_PAGE_TRENDINGTAGS,
				POP_WPAPI_PAGE_LOADERS_COMMENTS_FIELDS,
				POP_WPAPI_PAGE_LOADERS_COMMENTS_LAYOUTS,
			));
		}
		elseif ($thumbprint == POP_CDNCORE_THUMBPRINT_TAG) {

			$pages = array_filter(array(
				POP_WPAPI_PAGE_TAGS,
				POP_WPAPI_PAGE_TRENDINGTAGS,
				POP_WPAPI_PAGE_LOADERS_TAGS_FIELDS,
				POP_WPAPI_PAGE_LOADERS_TAGS_LAYOUTS,
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

			// For the "comments" tab in a single post: 
			// eg: getpop.org/en/posts/some-post-title/?tab=comments
			$pages = array_filter(array(
				POP_WPAPI_PAGE_COMMENTS,
			));
			foreach ($pages as $page) {
				
				$paramvalues[] = array(
					GD_URLPARAM_TAB, 
					GD_TemplateManager_Utils::get_page_path($page)
				);
			}

			// Fetch the comments through lazy-load when calling POP_WPAPI_PAGE_LOADERS_POSTS_FIELDS, such as Articles in the Details view
			// eg: https://www.mesym.com/en/loaders/posts/data/?pid[0]=21566&pid[1]=21542&pid[2]=21537&pid[3]=21523&pid[4]=21521&pid[5]=21472&pid[6]=21471&pid[7]=21470&pid[8]=21469&pid[9]=21468&pid[10]=21465&pid[11]=21464&fields[0]=recommendpost-count&fields[1]=recommendpost-count-plus1&fields[2]=comments-count&format=updatedata&target=main&module=settingsdata&output=json&theme=wassup&thememode=sliding&themestyle=swift
			$paramvalues[] = array(
				GD_URLPARAM_FIELDS, 
				'comments-count'
			);
		}
		
		return $paramvalues;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_WPAPI_CDN_Hooks();
