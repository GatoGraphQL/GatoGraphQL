<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_CategoryProcessors_SectionFilterHooks {

	function __construct() {

		add_filter(
			'wassup_section_taxonomyterms', 
			array($this, 'get_taxonomyterms')
		);
		add_filter(
			'wassup_webpostsection_cats', 
			array($this, 'get_cats')
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
				$pages = PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cat_pages(array(POP_CATEGORYPROCESSORS_CONFIGUTILS_WEBPOSTS));
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
			PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cats(array(POP_CATEGORYPROCESSORS_CONFIGUTILS_WEBPOSTS))
		);
	}

	function get_taxonomyterms($section_taxonomyterms) {

		if ($cats = PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cats(array(POP_CATEGORYPROCESSORS_CONFIGUTILS_WEBPOSTS))) {

			$section_taxonomyterms = array_merge_recursive(
				$section_taxonomyterms,
				array('category' => $cats)
			);
		}

		return $section_taxonomyterms;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_CategoryProcessors_SectionFilterHooks();
