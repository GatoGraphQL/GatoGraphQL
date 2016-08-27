<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_PROJECTS_MAP', PoP_ServerUtils::get_template_definition('scroll-projects-map'));
define ('GD_TEMPLATE_SCROLL_PROJECTS_HORIZONTALMAP', PoP_ServerUtils::get_template_definition('scroll-projects-horizontalmap'));

class GD_Custom_Template_Processor_CustomScrollMaps extends GD_Template_Processor_ScrollMapsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_PROJECTS_MAP,
			GD_TEMPLATE_SCROLL_PROJECTS_HORIZONTALMAP,
		);
	}


	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_SCROLL_PROJECTS_MAP => GD_TEMPLATE_SCROLLINNER_PROJECTS_MAP,
			GD_TEMPLATE_SCROLL_PROJECTS_HORIZONTALMAP => GD_TEMPLATE_SCROLLINNER_PROJECTS_HORIZONTALMAP,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	protected function get_description($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_SCROLL_PROJECTS_MAP:
			
				return sprintf(
					'<div class="pop-scrollformore bg-warning text-warning text-center row scroll-row"><small>%s</small></div>',
					__('Scroll down to load more results', 'poptheme-wassup-sectionprocessors')
				);
		}
	
		return parent::get_description($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CustomScrollMaps();