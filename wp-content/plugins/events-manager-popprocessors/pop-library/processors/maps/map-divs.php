<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_DIV', PoP_TemplateIDUtils::get_template_definition('em-map-div'));
define ('GD_TEMPLATE_MAPSTATICIMAGE_DIV', PoP_TemplateIDUtils::get_template_definition('em-mapstaticimage-div'));
define ('GD_TEMPLATE_MAPSTATICIMAGE_USERORPOST_DIV', PoP_TemplateIDUtils::get_template_definition('em-mapstaticimage-userorpost-div'));

class GD_Template_Processor_MapDivs extends GD_Template_Processor_MapDivsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_DIV,
			GD_TEMPLATE_MAPSTATICIMAGE_DIV,
			GD_TEMPLATE_MAPSTATICIMAGE_USERORPOST_DIV,
		);
	}

	function get_inner_templates($template_id) {

		$ret = parent::get_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_MAPSTATICIMAGE_DIV:
			
				$ret[] = GD_TEMPLATE_SCROLL_STATICIMAGE;
				break;

			case GD_TEMPLATE_MAPSTATICIMAGE_USERORPOST_DIV:

				$ret[] = GD_TEMPLATE_SCROLL_STATICIMAGE_USERORPOST;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MapDivs();