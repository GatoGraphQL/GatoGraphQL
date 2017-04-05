<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * CDN Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_CategoryProcessors_CDN_Hooks {

	function __construct() {

		add_filter(
			'PoP_CDNCore_Job_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
			array($this, 'get_thumbprint_partialpaths'),
			10,
			2
		);
	}

	function get_thumbprint_partialpaths($paths, $thumbprint) {
		
		if (in_array($thumbprint, array(
			POP_CDNCORE_THUMBPRINT_POST,
			POP_CDNCORE_THUMBPRINT_USER,
		))) {

			$pages = array_filter(array(
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS00,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS01,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS02,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS03,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS04,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS05,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS06,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS07,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS08,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS09,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYWEBPOSTS00,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYWEBPOSTS01,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYWEBPOSTS02,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYWEBPOSTS03,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYWEBPOSTS04,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYWEBPOSTS05,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYWEBPOSTS06,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYWEBPOSTS07,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYWEBPOSTS08,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYWEBPOSTS09,
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
new PoP_CategoryProcessors_CDN_Hooks();
