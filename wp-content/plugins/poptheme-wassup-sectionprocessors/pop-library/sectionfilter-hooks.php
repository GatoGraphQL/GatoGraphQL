<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_SectionProcessors_SectionFilterHooks {

	function __construct() {

		add_filter(
			'wassup_section_taxonomyterms', 
			array($this, 'get_taxonomyterms'),
			0
		);
		add_filter(
			'wassup_webpostsection_cats', 
			array($this, 'get_cats'),
			0
		);
		add_filter(
			'GD_FormInput_Sections:taxonomyterms:name', 
			array($this, 'get_taxonomyterms_name'),
			10,
			3
		);
		add_filter(
			'GD_FormInput_WebPostSections:cat:name', 
			array($this, 'get_cat_name'),
			10,
			2
		);
	}

	function get_cat_name($name, $cat) {

		return $this->get_taxonomyterms_name($name, 'category', $cat);
	}

	function get_taxonomyterms_name($name, $taxonomy, $term) {

		switch ($taxonomy) {
			
			case 'category':
				$pages = array(
					POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS,
					POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS,
					POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES,
					POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS,
					// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED,
				);
				if ($page = $pages[$term]) {
					return get_the_title($page);
				}
				break;
		}

		return $name;
	}

	function get_cats($cats) {

		return array_merge(
			$cats,
			array_filter(
				array(
					POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS,
					POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS,
					POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES,
				)
			)
		);
	}

	function get_taxonomyterms($section_taxonomyterms) {

		if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS) {
			$section_taxonomyterms = array_merge_recursive(
				$section_taxonomyterms,
				array('category' => array(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS))
			);
		}
		if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS) {
			$section_taxonomyterms = array_merge_recursive(
				$section_taxonomyterms,
				array('category' => array(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS))
			);
		}
		if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS) {
			$section_taxonomyterms = array_merge_recursive(
				$section_taxonomyterms,
				array('category' => array(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS))
			);
		}
		if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES) {
			$section_taxonomyterms = array_merge_recursive(
				$section_taxonomyterms,
				array('category' => array(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES))
			);
		}
		// if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED) {
		// 	$section_taxonomyterms = array_merge_recursive(
		// 		$section_taxonomyterms,
		// 		array('category' => array(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED))
		// 	);
		// }

		return $section_taxonomyterms;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_SectionProcessors_SectionFilterHooks();
