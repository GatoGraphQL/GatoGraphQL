<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class AgendaUrbana_CategoryProcessors_SectionFilterHooks {

	function __construct() {

		// Override the filters to remove "Featured" category from the Section Filters
		// This category is used only as a widget
		add_filter(
			'wassup_section_taxonomyterms', 
			array($this, 'get_taxonomyterms'),
			1000
		);
		add_filter(
			'wassup_webpostsection_cats', 
			array($this, 'get_cats'),
			1000
		);
	}

	function get_cats($cats) {

		// Remove the "Featured" category
		if (array_search(AGENDAURBANA_CAT_FEATURED, $cats) > -1) {
			array_splice($cats, array_search(AGENDAURBANA_CAT_FEATURED, $cats), 1);
		}
		return $cats;
	}

	function get_taxonomyterms($section_taxonomyterms) {

		// Remove the "Featured" category
		if (array_search(AGENDAURBANA_CAT_FEATURED, $section_taxonomyterms['category']) > -1) {
			array_splice($section_taxonomyterms['category'], array_search(AGENDAURBANA_CAT_FEATURED, $section_taxonomyterms['category']), 1);
		}

		return $section_taxonomyterms;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AgendaUrbana_CategoryProcessors_SectionFilterHooks();
