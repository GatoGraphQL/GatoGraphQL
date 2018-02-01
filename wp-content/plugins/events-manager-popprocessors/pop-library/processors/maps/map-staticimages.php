<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_STATICIMAGE', PoP_TemplateIDUtils::get_template_definition('em-map-staticimage'));
define ('GD_TEMPLATE_MAP_STATICIMAGE_USERORPOST', PoP_TemplateIDUtils::get_template_definition('em-map-staticimage-userorpost'));

class GD_Template_Processor_MapStaticImages extends GD_Template_Processor_MapStaticImagesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_STATICIMAGE,
			GD_TEMPLATE_MAP_STATICIMAGE_USERORPOST,
		);
	}

	function get_urlparam_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_MAP_STATICIMAGE:

				return GD_TEMPLATE_MAP_STATICIMAGE_URLPARAM;

			case GD_TEMPLATE_MAP_STATICIMAGE_USERORPOST:

				return GD_TEMPLATE_MAP_STATICIMAGE_LOCATIONS;
		}

		return parent::get_urlparam_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_MAP_STATICIMAGE:
			case GD_TEMPLATE_MAP_STATICIMAGE_USERORPOST:

				$this->append_att($template_id, $atts, 'class', 'img-responsive');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MapStaticImages();