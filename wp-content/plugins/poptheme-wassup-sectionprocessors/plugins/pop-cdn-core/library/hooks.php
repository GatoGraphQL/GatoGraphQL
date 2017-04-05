<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * CDN Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_SectionProcessors_CDN_Hooks {

	function __construct() {

		add_filter(
			'PoP_CDNCore_Job_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
			array($this, 'get_thumbprint_partialpaths'),
			10,
			2
		);
	}

	function get_thumbprint_partialpaths($paths, $thumbprint) {
		
		if ($thumbprint == POP_CDNCORE_THUMBPRINT_POST) {

			$pages = array_filter(array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG,
			));
			foreach ($pages as $page) {

				$paths[] = trailingslashit(GD_TemplateManager_Utils::get_page_path($page));
			}
		}
		elseif ($thumbprint == POP_CDNCORE_THUMBPRINT_USER) {

			$pages = array_filter(array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_WHOWEARE,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSPONSORS,
			));
			foreach ($pages as $page) {

				$paths[] = trailingslashit(GD_TemplateManager_Utils::get_page_path($page));
			}
		}
		elseif ($thumbprint == POP_CDNCORE_THUMBPRINT_PAGE) {

			$pages = array_filter(array(
				// POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_HOWTOUSEWEBSITE,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_CONTENTGUIDELINES,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURMISSION,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSTORY,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_VOLUNTEERWITHUS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_INTHEMEDIA,
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
new PoP_SectionProcessors_CDN_Hooks();

